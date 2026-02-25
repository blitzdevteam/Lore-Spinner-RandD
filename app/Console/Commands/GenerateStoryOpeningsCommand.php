<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\Story\StoryOpeningGeneratorJob;
use App\Models\Story;
use Illuminate\Console\Command;

final class GenerateStoryOpeningsCommand extends Command
{
    protected $signature = 'stories:generate-openings
                            {--force : Regenerate openings even for stories that already have one}
                            {--sync : Run synchronously instead of dispatching to queue}
                            {--story= : Generate opening for a specific story ID only}';

    protected $description = 'Generate cinematic Lorespinner opening narrations for stories.';

    public function handle(): int
    {
        $query = Story::query()->whereNotNull('system_prompt');

        if ($storyId = $this->option('story')) {
            $query->where('id', $storyId);
        }

        if (! $this->option('force')) {
            $query->where(function ($q) {
                $q->whereNull('opening')
                    ->orWhere('opening', '')
                    ->orWhereRaw("opening NOT LIKE '%Lorespinner%'");
            });
        }

        $stories = $query->get();

        if ($stories->isEmpty()) {
            $this->info('No stories need opening generation.');

            return self::SUCCESS;
        }

        $this->info("Generating openings for {$stories->count()} stories...");

        $stories->each(function (Story $story): void {
            $this->line("  → {$story->title}");

            if ($this->option('sync')) {
                StoryOpeningGeneratorJob::dispatchSync($story);
                $story->refresh();
                $this->info("    ✓ Generated (" . strlen($story->opening ?? '') . " chars)");
            } else {
                StoryOpeningGeneratorJob::dispatch($story);
                $this->line("    Dispatched to queue.");
            }
        });

        if (! $this->option('sync')) {
            $this->newLine();
            $this->info('All jobs dispatched. Run your queue worker to process them.');
        }

        return self::SUCCESS;
    }
}
