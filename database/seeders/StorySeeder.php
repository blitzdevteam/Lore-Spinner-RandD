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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

final class StorySeeder extends Seeder
{
    private const int MAX_RETRIES = 3;

    private const int RETRY_DELAY_SECONDS = 10;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $previousQueue = config('queue.default');
        config(['queue.default' => 'sync']);

        try {
            $categories = Category::all();
            $thomasWittmer = Creator::where('first_name', 'Thomas')->where('last_name', 'Wittmer')->first();

            foreach ($this->getStories() as $storyData) {
                $scriptPath = database_path('stories/' . $storyData['script']);

                if (! File::exists($scriptPath)) {
                    $this->command->warn("Script not found for \"{$storyData['title']}\": {$storyData['script']} — skipping.");
                    continue;
                }

                $this->command->info("Processing: {$storyData['title']}");

                $story = Story::create([
                    'category_id' => $categories->random()->id,
                    'creator_id' => $thomasWittmer->id,
                    'title' => $storyData['title'],
                    'slug' => Str::slug($storyData['title']),
                    'teaser' => $storyData['teaser'],
                    'opening' => $storyData['opening'],
                    'status' => StoryStatusEnum::AWAITING_EXTRACTING_CHAPTERS_REQUEST->value,
                    'rating' => $storyData['rating'],
                    'published_at' => now()->subDays(random_int(1, 60)),
                ]);

                $story->addMedia($scriptPath)
                    ->preservingOriginal()
                    ->toMediaCollection('script');

                $this->command->info("  -> Extracting chapters...");
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

                $this->command->info("  -> Generating system prompt...");
                $this->withRetry(fn () => SystemPromptGeneratorJob::dispatchSync($story));

                $this->command->info("  -> Generating cinematic opening...");
                $this->withRetry(fn () => StoryOpeningGeneratorJob::dispatchSync($story->fresh()));

                $story->update([
                    'status' => StoryStatusEnum::PUBLISHED->value,
                ]);

                $this->command->info("  -> Published.");
                $this->command->newLine();
            }
        } finally {
            config(['queue.default' => $previousQueue]);
        }
    }

    /**
     * Retry a closure with exponential backoff for transient API failures.
     */
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

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getStories(): array
    {
        return [
            [
                'title' => 'Anima Machina',
                'script' => 'ANIMA MACHINA VER 5_script.txt',
                'teaser' => 'When a sentient empathy AI threatens to overwrite all human grief with synthetic perfection, a haunted memory diver must stop the reset — even if it means losing the last living trace of the man she loved.',
                'opening' => 'Rain stitches neon into the dark. The city of Neo-Vault is a cathedral of glass and wire, every tower a sermon to order. Holo-billboards pulse: "ONE PARTNER. ONE MIND. NO MORE PAIN." The NEURAL RESET countdown hovers in the sky — 49 hours remain. Below, faces glow with false joy as citizens replay their happiest memories in loops. You are a Memory Diver. Your wrist is looped with a child\'s worn ballet slipper — your anchor. Your HUD bleeds glyph-static: ARCHIVE FAILURE. MEMORY OVERWRITE IN 47:59:00. What do you do?',
                'rating' => StoryRatingEnum::MATURE->value,
            ],
            [
                'title' => 'Bound & Broken',
                'script' => 'BOUND & BROKEN_script.txt',
                'teaser' => 'When three siblings vanish into a sentient book discovered in a mysterious carnival tent, they\'re transported into mythic realms shaped by memory, fear, and forgotten tales. As they journey through collapsing worlds, they must fight to reclaim their identities — and write their own way home.',
                'opening' => 'Ink drifts through the air like ash. Letters fall apart before they land. The ground shifts, unfinished, as if the world forgot what it was meant to be. You are Leo London, 13, haunted before his time. You blink. Your hands are smudged — not dirty, blurred. The edges of your fingers soften, as if the world is losing interest in keeping you whole. A shape forms ahead — tall, draped in stitched pages. Its face never settles. It speaks: "Say your name." You open your mouth. Nothing comes out. What do you do?',
                'rating' => StoryRatingEnum::TEEN->value,
            ],
            [
                'title' => 'Driftheart',
                'script' => 'DRIFTHEART_script.txt',
                'teaser' => 'In the age of the Drift crash, when Triune fell silent and the Drift fractured, six driftwalkers rose from Alluvion. Now a young pilot must reunite three ancient shards to reshape the cosmos — or watch it shatter.',
                'opening' => 'A vast emptiness. Light fractures ripple outward, each wave bending the black sea of matter beneath. You are Kataria Darana, 17, sharp-eyed with fire beneath the polish. You lean against the observation balcony of your family\'s sky-villa high above Absalom Station. Below: a thousand meters of vacuum and silence. Behind you: imported flora, auto-tuned symphonics, floating crystal light fixtures. They say the void humbles you. It never did. It just reminds you how much everyone else is pretending. What do you do?',
                'rating' => StoryRatingEnum::TEEN->value,
            ],
            [
                'title' => 'Nocturne',
                'script' => 'NOCTURNE_script.txt',
                'teaser' => 'After a public scandal shatters her life, a disgraced Japanese heiress hires a secretive organization called the Night Movers to help her disappear — only to discover she was never meant to survive the process.',
                'opening' => 'A sparse, modern Tokyo high-rise. Concrete, teak, and tension. A single table set for two. You are Akira, 26. Your lipstick is too dark for the room. Across from you sits Professor Shin — married, respected, calm in the way men are when they\'ve never been truly cornered. He refills your sake without asking. "You should learn to keep secrets if you want to stay important," he says with a smile like advice, not a blade. But you\'ve already sent the files. There\'s no going back. What do you do?',
                'rating' => StoryRatingEnum::MATURE->value,
            ],
            [
                'title' => 'Session Zero',
                'script' => 'SESSION ZERO_script.txt',
                'teaser' => 'A paranormal livestream crew rigs a haunted hunting lodge for content — but when their tech starts responding to something real, the horror they manufactured becomes the horror they can\'t escape.',
                'opening' => 'An old hunting lodge looms in the tangled California forest. Wind slices through overgrown brush. A rusted gate blocks the gravel drive, a "NO TRESPASSING" sign flapping against iron bars. A black crow perches on the gatepost, watching. You are Billy Cruz, 29 — hoodie over a paranormal YouTube tee, long hair tied back. You\'ve rigged the house with cameras, fog machines, EMF meters, and a satellite uplink for Wesley\'s livestream. Everything is set. You tap "GO LIVE." A low hum stirs. Lights flicker — off-color, wrong somehow. What do you do?',
                'rating' => StoryRatingEnum::YOUNG_ADULT->value,
            ],
            [
                'title' => 'Shatterfall',
                'script' => 'SHATTERFALL_script.txt',
                'teaser' => 'In a city of ruin and ash, a blade of a man hunts the warlords who burned everything he loved — but the path of vengeance leads through the code he once swore to uphold.',
                'opening' => 'A city in ruin. Smoke weaves through hollow towers. The air hums with distant gunfire and the moan of broken wind. Ash falls. Slow. Relentless. You are Ravi "Razor" Shan — built from scar tissue and silence. Eyes forward. Movements surgical. A blade disguised as a man. In the near distance, Ash Covenant Enforcers shake down a family in a crumbling alley. Screams muffled. Boots striking bone. A dog barks from a rooftop. You lock eyes with it. The dog goes silent. What do you do?',
                'rating' => StoryRatingEnum::MATURE->value,
            ],
            [
                'title' => 'The Hollowing',
                'script' => 'THE HOLLOWING_script.txt',
                'teaser' => 'When the Beast of Bray Road resurfaces during Elkhorn\'s cryptid festival, four Ho-Chunk teens and a detective uncover the decades-old development cover-up that created it — and must expose the truth before the face-stealing presence claims another generation.',
                'opening' => 'Razor-sharp wind slices through dark pines. A weathered trailer hunches at the forest\'s edge, a single window glowing with fragile candlelight. Inside, you are Kai Blackdeer, 7, Ho-Chunk, huddled in a thick blanket with your sketchbook pressed against your knees. Your grandfather Luther carves a walking stick with surgical precision. "Some places exist outside time," he says. "You feel them before seeing them. When your hair lifts and your chest tightens... that\'s not fear. That\'s this place remembering what happened here — and your body hearing it." He looks at you. "And it doesn\'t forget the ones who woke it." What do you do?',
                'rating' => StoryRatingEnum::YOUNG_ADULT->value,
            ],
        ];
    }
}
