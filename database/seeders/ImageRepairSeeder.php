<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

final class ImageRepairSeeder extends Seeder
{
    public function run(): void
    {
        $previousQueue = config('queue.default');
        config(['queue.default' => 'sync']);

        try {
            $this->clearBrokenCovers();

            Artisan::call('images:generate-missing');
            $this->command->info('Image repair complete.');
        } finally {
            config(['queue.default' => $previousQueue]);
        }
    }

    private function clearBrokenCovers(): void
    {
        $cleared = 0;

        foreach (Story::with('media')->get() as $story) {
            $cover = $story->getFirstMedia('cover');

            if ($cover && ! file_exists($cover->getPath())) {
                $story->clearMediaCollection('cover');
                $cleared++;
                $this->command->warn("Cleared broken cover for story: {$story->title}");
            }
        }

        foreach (Chapter::with('media')->get() as $chapter) {
            $cover = $chapter->getFirstMedia('cover');

            if ($cover && ! file_exists($cover->getPath())) {
                $chapter->clearMediaCollection('cover');
                $cleared++;
            }
        }

        $this->command->info("Cleared {$cleared} broken cover records.");
    }
}
