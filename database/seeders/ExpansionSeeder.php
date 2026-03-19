<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Chapter\ChapterStatusEnum;
use App\Enums\Story\StoryRatingEnum;
use App\Enums\Story\StoryStatusEnum;
use App\Jobs\Chapter\ChapterExtractorJob;
use App\Jobs\Event\EventExtractorJob;
use App\Jobs\Story\StoryOpeningGeneratorJob;
use App\Jobs\Story\SystemPromptGeneratorJob;
use App\Models\Category;
use App\Models\Creator;
use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use Throwable;

/**
 * Production-safe expansion seeder.
 *
 * Creates new creators, categories, reassigns existing stories to their
 * correct creators/categories, and onboards new stories through the full
 * AI extraction pipeline — all without wiping existing data.
 *
 * Usage: php artisan db:seed --class=ExpansionSeeder
 */
final class ExpansionSeeder extends Seeder
{
    private const int MAX_RETRIES = 3;

    private const int RETRY_DELAY_SECONDS = 10;

    public function run(): void
    {
        $previousQueue = config('queue.default');
        config(['queue.default' => 'sync']);

        try {
            $this->seedCategories();
            $this->seedCreators();
            $this->reassignExistingStories();
            $this->seedNewStories();

            $this->command->newLine();
            $this->command->info('Generating missing images...');
            Artisan::call('images:generate-missing');

            $this->command->info('Expansion complete.');
        } finally {
            config(['queue.default' => $previousQueue]);
        }
    }

    // ── Categories ──────────────────────────────────────────────────

    private function seedCategories(): void
    {
        $this->command->info('Ensuring categories...');

        $categories = [
            'Science Fiction',
            'Action Thriller',
            'Dark Fantasy',
            'Horror',
            'Thriller',
            'Historical Adventure',
            'Fantasy Adventure',
            'Adventure',
            'Techno-Thriller',
            'Supernatural Thriller',
            'Military Drama',
            'Dystopian',
            'Mystery',
            'Drama',
            'Cyberpunk',
        ];

        $created = 0;

        foreach ($categories as $title) {
            $result = Category::firstOrCreate(['title' => $title]);

            if ($result->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info("  -> {$created} new categories created, ".(count($categories) - $created).' already existed.');
    }

    // ── Creators ────────────────────────────────────────────────────

    private function seedCreators(): void
    {
        $this->command->info('Ensuring creators...');

        foreach ($this->getCreators() as $data) {
            $creator = null;
            $wasNew = false;

            Creator::withoutEvents(function () use ($data, &$creator, &$wasNew): void {
                $creator = Creator::firstOrCreate(
                    ['email' => $data['email']],
                    [
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'username' => $data['username'],
                        'password' => 'password',
                        'bio' => $data['bio'],
                    ]
                );
                $wasNew = $creator->wasRecentlyCreated;
            });

            DB::table('creators')
                ->where('id', $creator->id)
                ->update([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'bio' => $data['bio'],
                ]);

            $avatarPath = database_path('stories/avatars/'.$data['avatar']);

            if (File::exists($avatarPath)) {
                if ($creator->getFirstMedia('avatar')) {
                    $creator->clearMediaCollection('avatar');
                }

                $creator->addMedia($avatarPath)
                    ->preservingOriginal()
                    ->usingFileName('avatar-'.$creator->id.'.'.pathinfo($avatarPath, PATHINFO_EXTENSION))
                    ->toMediaCollection('avatar', 'public');
            }

            $label = $wasNew ? 'Created' : 'Updated';
            $this->command->info("  -> {$label}: {$data['first_name']} {$data['last_name']}");
        }
    }

    // ── Reassign existing stories ───────────────────────────────────

    private function reassignExistingStories(): void
    {
        $this->command->info('Reassigning existing stories...');

        foreach ($this->getExistingStoryUpdates() as $update) {
            $story = Story::where('title', $update['title'])->first();

            if (! $story) {
                $this->command->warn("  -> Story not found: {$update['title']} — skipping.");

                continue;
            }

            $creator = Creator::where('email', $update['creator_email'])->first();
            $category = Category::where('title', $update['category'])->first();

            if (! $creator || ! $category) {
                $this->command->warn("  -> Creator or category missing for: {$update['title']} — skipping.");

                continue;
            }

            $story->update([
                'creator_id' => $creator->id,
                'category_id' => $category->id,
                'teaser' => $update['teaser'],
            ]);

            $this->command->info("  -> {$update['title']} → {$creator->full_name} / {$category->title}");
        }
    }

    // ── New stories (full pipeline) ─────────────────────────────────

    private function seedNewStories(): void
    {
        $newStories = $this->getNewStories();

        if (empty($newStories)) {
            $this->command->info('No new stories to process.');

            return;
        }

        $this->command->info('Processing new stories...');

        foreach ($newStories as $storyData) {
            if (Story::where('title', $storyData['title'])->exists()) {
                $this->command->info("  -> Already exists: {$storyData['title']} — skipping.");

                continue;
            }

            $scriptPath = database_path('stories/'.$storyData['script']);

            if (! File::exists($scriptPath) && isset($storyData['source_pdf'])) {
                $pdfPath = database_path('stories/'.$storyData['source_pdf']);

                if (File::exists($pdfPath)) {
                    $this->command->info("  -> Converting PDF: {$storyData['source_pdf']}");
                    $this->convertPdf($pdfPath, $scriptPath);
                }
            }

            if (! File::exists($scriptPath)) {
                $this->command->warn("  -> Script not found for \"{$storyData['title']}\": {$storyData['script']} — skipping.");

                continue;
            }

            $creator = Creator::where('email', $storyData['creator_email'])->first();
            $category = Category::where('title', $storyData['category'])->first();

            if (! $creator || ! $category) {
                $this->command->warn("  -> Creator or category missing for: {$storyData['title']} — skipping.");

                continue;
            }

            $this->command->newLine();
            $this->command->info("Processing: {$storyData['title']}");

            $story = Story::create([
                'category_id' => $category->id,
                'creator_id' => $creator->id,
                'title' => $storyData['title'],
                'slug' => Str::slug($storyData['title']),
                'teaser' => $storyData['teaser'],
                'opening' => null,
                'status' => StoryStatusEnum::AWAITING_EXTRACTING_CHAPTERS_REQUEST->value,
                'rating' => $storyData['rating'],
                'published_at' => now()->subDays(random_int(1, 60)),
            ]);

            $story->addMedia($scriptPath)
                ->preservingOriginal()
                ->toMediaCollection('script');

            $this->command->info('  -> Extracting chapters...');
            $this->withRetry(fn () => ChapterExtractorJob::dispatchSync($story->fresh()));

            $story->refresh();
            $this->command->info("  -> {$story->chapters()->count()} chapters extracted.");

            foreach ($story->chapters()->orderBy('position')->get() as $chapter) {
                $this->command->info("  -> Extracting events for: {$chapter->title}");
                $this->withRetry(function () use ($chapter): void {
                    $chapter->events()->delete();
                    EventExtractorJob::dispatchSync($chapter->fresh());
                });
                $chapter->refresh();

                if ($chapter->events()->count() === 0 && $chapter->status !== ChapterStatusEnum::READY_TO_PLAY) {
                    $chapter->update(['status' => ChapterStatusEnum::READY_TO_PLAY]);
                }

                $this->command->info("     {$chapter->events()->count()} events extracted.");
            }

            $this->command->info('  -> Generating system prompt...');
            $this->withRetry(fn () => SystemPromptGeneratorJob::dispatchSync($story));

            $this->command->info('  -> Generating cinematic opening...');
            $this->withRetry(fn () => StoryOpeningGeneratorJob::dispatchSync($story->fresh()));

            $story->update([
                'status' => StoryStatusEnum::PUBLISHED->value,
            ]);

            $this->command->info('  -> Published.');
        }
    }

    // ── PDF conversion ──────────────────────────────────────────────

    private function convertPdf(string $pdfPath, string $txtPath): void
    {
        $parser = new Parser;
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();

        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/^\d+\.?\s*$/m', '', $text);
        $text = preg_replace("/\n{4,}/", "\n\n\n", $text);
        $text = implode("\n", array_map('rtrim', explode("\n", $text)));
        $text = mb_trim($text)."\n";

        File::put($txtPath, $text);

        $this->command->info('     -> Saved: '.basename($txtPath).' ('.mb_strlen($text).' bytes)');
    }

    // ── Retry helper ────────────────────────────────────────────────

    private function withRetry(callable $callback): void
    {
        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            try {
                $callback();

                return;
            } catch (Throwable $e) {
                if ($attempt === self::MAX_RETRIES) {
                    throw $e;
                }

                $delay = self::RETRY_DELAY_SECONDS * $attempt;
                $this->command->warn("     Attempt {$attempt} failed: {$e->getMessage()}");
                $this->command->warn("     Retrying in {$delay}s...");
                sleep($delay);
            }
        }
    }

    // ── Data ────────────────────────────────────────────────────────

    /**
     * @return list<array<string, string>>
     */
    private function getCreators(): array
    {
        return [
            [
                'first_name' => 'Thomas',
                'last_name' => 'Wittmer',
                'username' => 'thomaswittmer',
                'email' => 'thomas@lorespinner.com',
                'bio' => 'Mythic blockbuster storyteller building high-concept cinematic worlds fueled by awe, danger, and emotionally charged spectacle.',
                'avatar' => 'THOMAS WITTMER - PROFILE PIC.jpg',
            ],
            [
                'first_name' => 'Hilton',
                'last_name' => 'Williams',
                'username' => 'hiltonwilliams',
                'email' => 'hilton@lorespinner.com',
                'bio' => 'Writer of elevated, atmospheric stories where mystery, identity, and human fracture unfold with haunting emotional depth.',
                'avatar' => 'Hilton Williams - PROFILE PIC.jpg',
            ],
            [
                'first_name' => 'Rand',
                'last_name' => 'Soares',
                'username' => 'randsoares',
                'email' => 'rand@lorespinner.com',
                'bio' => 'Hollywood veteran and cinematic world-builder crafting bold, high-concept adventures defined by scale, heart, danger, and timeless blockbuster storytelling.',
                'avatar' => 'RAND SOARES - PROFILE PIC.jpg',
            ],
            [
                'first_name' => 'FREEP!',
                'last_name' => '',
                'username' => 'freep',
                'email' => 'freep@lorespinner.com',
                'bio' => 'Hollywood writing team creating high-impact television worlds where serialized mythology, procedural engines, and commercial concept collide.',
                'avatar' => 'FREEP1 - PROFILE PIC.jpg',
            ],
            [
                'first_name' => 'The Classics, Unbound',
                'last_name' => '',
                'username' => 'theclassicsunbound',
                'email' => 'classics@lorespinner.com',
                'bio' => "Enter the world's most iconic classic stories—now immersive, interactive adventures where your choices reshape timeless legends.",
                'avatar' => 'THE CLASSICS, UNBOUND - PROFILE PIC.jpg',
            ],
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    private function getExistingStoryUpdates(): array
    {
        return [
            [
                'title' => 'Shatterfall',
                'creator_email' => 'thomas@lorespinner.com',
                'category' => 'Action Thriller',
                'teaser' => 'In a post-collapse city ruled by brutal dogma and industrial ruin, a war-scarred assassin returns from exile to exact ritual vengeance on the six betrayers who destroyed the only person who ever made him human.',
            ],
            [
                'title' => 'Anima Machina',
                'creator_email' => 'thomas@lorespinner.com',
                'category' => 'Science Fiction',
                'teaser' => 'When a sentient empathy AI threatens to overwrite human grief with synthetic perfection, a haunted memory diver must stop the reset before she loses the last living trace of the man she loved.',
            ],
            [
                'title' => 'Driftheart',
                'creator_email' => 'thomas@lorespinner.com',
                'category' => 'Fantasy Adventure',
                'teaser' => 'After stealing a mystical shard and fleeing her privileged life, a rebellious young woman must navigate dangerous alliances and ancient Vault mysteries before powerful forces use the shards to reshape the universe.',
            ],
            [
                'title' => 'Bound & Broken',
                'creator_email' => 'thomas@lorespinner.com',
                'category' => 'Dark Fantasy',
                'teaser' => 'When three grieving siblings are pulled into a sentient book hidden inside a mysterious carnival, they are scattered across mythic realms and must resist the roles written for them before the story erases who they are forever.',
            ],
            [
                'title' => 'The Hollowing',
                'creator_email' => 'hilton@lorespinner.com',
                'category' => 'Horror',
                'teaser' => "When a group of teens disturb a sacred burial mound during their small town's myth-based festival, they awaken an ancient entity that feeds on blood memory and threatens to hollow their community from within.",
            ],
            [
                'title' => 'Nocturne',
                'creator_email' => 'hilton@lorespinner.com',
                'category' => 'Thriller',
                'teaser' => 'After a public scandal shatters her life, a disgraced Japanese heiress discovers the organization helping her disappear is part of an ancient cult that erases and rewrites identity.',
            ],
            [
                'title' => 'Session Zero',
                'creator_email' => 'hilton@lorespinner.com',
                'category' => 'Horror',
                'teaser' => 'When a group of friends gather for one final D&D livestream in an allegedly haunted house, they become trapped in a deadly supernatural game that feeds on trauma, confession, and truth.',
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getNewStories(): array
    {
        return [
            // ── Thomas Wittmer
            [
                'title' => 'Backchannel: Dead Air',
                'creator_email' => 'thomas@lorespinner.com',
                'category' => 'Action Thriller',
                'script' => 'BACKCHANNEL DEAD AIR_script.txt',
                'teaser' => "When a livestream justice crew's latest sting is hijacked and turned into a real-time public bounty hunt, their brilliant leader must keep her team alive long enough to expose the architect behind it.",
                'rating' => StoryRatingEnum::MATURE->value,
            ],

            // ── Rand Soares
            [
                'title' => "Hemingway's War",
                'creator_email' => 'rand@lorespinner.com',
                'category' => 'Historical Adventure',
                'script' => 'HEMINGWAYS WAR_script.txt',
                'source_pdf' => "Rand Soares - STORIES/HEMINGWAY'S WAR/Hemingway's War 5-22-2025.pdf",
                'teaser' => 'During World War II, Ernest Hemingway defies his role as a war correspondent, builds his own band of irregular fighters, and charges toward Paris in a reckless bid to liberate the Ritz before the Allied Army.',
                'rating' => StoryRatingEnum::MATURE->value,
            ],
            [
                'title' => 'High Stakes',
                'creator_email' => 'rand@lorespinner.com',
                'category' => 'Fantasy Adventure',
                'script' => 'HIGH STAKES_script.txt',
                'source_pdf' => 'Rand Soares - STORIES/HIGH STAKES/High Stakes.pdf',
                'teaser' => 'A fearless Wall Street thrill seeker enters the secret interdimensional game he has spent years trying to conquer, only to discover his supposedly dead best friend is alive inside a deadly world no one was ever meant to escape.',
                'rating' => StoryRatingEnum::YOUNG_ADULT->value,
            ],
            [
                'title' => 'Pieces of Eight',
                'creator_email' => 'rand@lorespinner.com',
                'category' => 'Adventure',
                'script' => 'PIECES OF EIGHT_script.txt',
                'source_pdf' => 'Rand Soares - STORIES/PIECES OF EIGHT/Pieces of Eight.pdf',
                'teaser' => 'A debt-ridden Florida Keys dive-shop owner and his son finally locate a legendary treasure ship, only to become targets of a brutal modern pirate king determined to steal the fortune for himself.',
                'rating' => StoryRatingEnum::YOUNG_ADULT->value,
            ],
            [
                'title' => 'Time Machine',
                'creator_email' => 'rand@lorespinner.com',
                'category' => 'Science Fiction',
                'script' => 'TIME MACHINE_script.txt',
                'source_pdf' => 'Rand Soares - STORIES/TIME MACHINE/Time Machine.pdf',
                'teaser' => 'After accidentally creating a working time machine for his college thesis, a disgraced young physicist is recruited by a visionary billionaire to build a full-scale version before powerful forces seize control of the past.',
                'rating' => StoryRatingEnum::TEEN->value,
            ],

            // ── FREEP!
            [
                'title' => 'B.U.G.S.',
                'creator_email' => 'freep@lorespinner.com',
                'category' => 'Techno-Thriller',
                'script' => 'BUGS_script.txt',
                'source_pdf' => 'FREEP1 - STORIES/BUGS/BUGS.pdf',
                'teaser' => 'After taking a Homeland Security job tracing an attack on the national power grid, a renegade team of underground operatives uncovers a nuclear smuggling plot tied to a far larger shadow conspiracy.',
                'rating' => StoryRatingEnum::MATURE->value,
            ],
            [
                'title' => 'Dream Police',
                'creator_email' => 'freep@lorespinner.com',
                'category' => 'Supernatural Thriller',
                'script' => 'DREAM POLICE_script.txt',
                'source_pdf' => 'FREEP1 - STORIES/CROSSOVERS : DREAM POLICE/Dream Police-2.pdf',
                'teaser' => 'When a black-ops agent who polices the dream world loses his partner to an impossible killer, he must hunt a rogue scientist weaponizing dreams before the boundary between sleep and reality collapses.',
                'rating' => StoryRatingEnum::MATURE->value,
            ],
            [
                'title' => 'Necropolis',
                'creator_email' => 'freep@lorespinner.com',
                'category' => 'Supernatural Thriller',
                'script' => 'NECROPOLIS_script.txt',
                'source_pdf' => 'FREEP1 - STORIES/NECROPOLIS/Necropolis.pdf',
                'teaser' => 'After dying in a catastrophic train bombing, a skeptical federal investigator awakens as a legendary Shadow Walker and is thrust into a hidden war between angels and demons over the gates of Hell.',
                'rating' => StoryRatingEnum::MATURE->value,
            ],
            [
                'title' => "PJ's",
                'creator_email' => 'freep@lorespinner.com',
                'category' => 'Military Drama',
                'script' => 'PJS_script.txt',
                'source_pdf' => "FREEP1 - STORIES/PJ'S/PJ's.pdf",
                'teaser' => "After their beloved team leader is killed during a covert extraction, an elite squad of U.S. Air Force Pararescuemen must regroup under new leadership and save lives in the world's deadliest crises.",
                'rating' => StoryRatingEnum::MATURE->value,
            ],
            [
                'title' => 'Wasteland',
                'creator_email' => 'freep@lorespinner.com',
                'category' => 'Dystopian',
                'script' => 'WASTELAND_script.txt',
                'source_pdf' => 'FREEP1 - STORIES/WASTELAND/Wasteland.pdf',
                'teaser' => "After uncovering a horrifying secret inside a futuristic waste system, a soft-spoken engineer is dumped into a hidden Sahara wasteland where society's discarded must fight to survive and escape.",
                'rating' => StoryRatingEnum::YOUNG_ADULT->value,
            ],
        ];
    }
}
