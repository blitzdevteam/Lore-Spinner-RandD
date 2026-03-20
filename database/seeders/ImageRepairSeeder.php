<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Jobs\Chapter\ChapterCoverGeneratorJob;
use App\Jobs\Story\StoryCoverGeneratorJob;
use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Database\Seeder;
use Throwable;

final class ImageRepairSeeder extends Seeder
{
    public function run(): void
    {
        $previousQueue = config('queue.default');
        config(['queue.default' => 'sync']);

        try {
            $this->generateStoryCovers();
            $this->generateChapterCovers();
            $this->command->info('Image repair complete.');
        } finally {
            config(['queue.default' => $previousQueue]);
        }
    }

    private function generateStoryCovers(): void
    {
        $stories = Story::query()
            ->whereDoesntHave('media', fn ($q) => $q->where('collection_name', 'cover'))
            ->get();

        $this->command->info("Generating covers for {$stories->count()} stories...");

        foreach ($stories as $story) {
            try {
                $this->command->info("  -> Story cover: {$story->title}");
                StoryCoverGeneratorJob::dispatchSync($story);
                $this->command->info('     Done.');
            } catch (Throwable $e) {
                $this->command->error("     Failed: {$e->getMessage()}");
            }
        }
    }

    private function generateChapterCovers(): void
    {
        $chapters = Chapter::query()
            ->with('story')
            ->whereDoesntHave('media', fn ($q) => $q->where('collection_name', 'cover'))
            ->get();

        $this->command->info("Generating covers for {$chapters->count()} chapters...");

        foreach ($chapters as $chapter) {
            try {
                $this->command->info("  -> Chapter cover: {$chapter->title} ({$chapter->story?->title})");
                ChapterCoverGeneratorJob::dispatchSync($chapter);
            } catch (Throwable $e) {
                $this->command->error("     Failed: {$e->getMessage()}");
            }
        }
    }
}
