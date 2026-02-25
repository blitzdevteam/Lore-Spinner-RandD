<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Comment\CommentStatusEnum;
use App\Models\Comment;
use App\Models\Creator;
use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;

final class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $stories = Story::query()
            ->select(['id', 'title', 'teaser'])
            ->get();

        $users = User::select('id')->get();
        $creators = Creator::select('id')->get();

        foreach ($stories as $story) {
            $commentCount = random_int(5, 12);
            $comments = $this->generateCommentsForStory($story, $commentCount);

            foreach ($comments as $content) {
                $isCreatorAuthor = fake()->boolean(30);
                $status = fake()->randomElement(CommentStatusEnum::values());
                $dateThisMonth = fake()->dateTimeThisMonth();

                Comment::create([
                    'author_id' => $isCreatorAuthor ? $creators->random()->id : $users->random()->id,
                    'author_type' => $isCreatorAuthor ? Creator::class : User::class,
                    'commentable_id' => $story->id,
                    'commentable_type' => Story::class,
                    'content' => $content,
                    'status' => $status,
                    'approved_at' => $status === CommentStatusEnum::APPROVED->value ? $dateThisMonth : null,
                    'created_at' => Carbon::make($dateThisMonth)?->subDays(random_int(1, 30)),
                    'updated_at' => $dateThisMonth,
                ]);
            }

            $this->command->info("Generated {$commentCount} comments for \"{$story->title}\"");
        }
    }

    /**
     * @return list<string>
     */
    private function generateCommentsForStory(Story $story, int $count): array
    {
        $response = Prism::text()
            ->using(Provider::OpenAI, 'gpt-4o-mini')
            ->withSystemPrompt(<<<'SYSTEM'
            You generate realistic user comments for Lore Spinner, an interactive storytelling platform.
            Users read through AI-narrated stories chapter by chapter. The experience is immersive and 
            linear — there are NO multiple endings, NO branching paths, NO replaying for different outcomes.
            The interactivity comes from the AI narration responding to the reader, making the story feel 
            alive and personal. Think of it like reading an incredible novel that feels like it's speaking 
            directly to you.
            
            Each comment should read like a real person wrote it after reading/experiencing the story.
            
            Rules:
            - Write from a first-person reader/player perspective
            - Reference specific scenes, characters, moments, atmosphere, or emotional beats from the story description
            - Focus on: the quality of the writing, how immersive it felt, the story itself, the characters, the atmosphere, emotional impact, pacing, tension
            - Vary the tone: some enthusiastic, some thoughtful, some casual, some short and punchy
            - Vary the length: some short (1 sentence), some medium (2-3 sentences)
            - Never use the exact story title in quotes
            - No hashtags, no emojis, no marketing language
            - Sound like real internet comments — casual grammar is fine, not everything has to be perfectly written
            - DO NOT mention: multiple endings, replaying for different choices, branching paths, different outcomes, alternate routes, replayability. These features do not exist.
            - DO NOT redefine or describe what the platform is. Just comment on the story.
            - Output ONLY the comments, one per line, separated by newlines. No numbering, no bullets, no prefixes.
            SYSTEM)
            ->withPrompt("Story: {$story->title}\nDescription: {$story->teaser}\n\nGenerate exactly {$count} unique player comments for this story. One comment per line, no numbering or bullets.")
            ->withClientOptions(['timeout' => 60])
            ->asText();

        $lines = array_values(
            array_filter(
                array_map('trim', explode("\n", $response->text)),
                fn (string $line): bool => strlen($line) > 10
            )
        );

        if (count($lines) < $count) {
            $lines = array_pad($lines, $count, "Really enjoyed this one. The atmosphere kept me hooked from start to finish.");
        }

        return array_slice($lines, 0, $count);
    }
}
