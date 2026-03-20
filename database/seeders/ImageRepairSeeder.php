<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class ImageRepairSeeder extends Seeder
{
    public function run(): void
    {
        $coverMap = [
            "Hemingway's War" => 'hemingways-war.png',
            'High Stakes' => 'high-stakes.png',
            'Pieces of Eight' => 'pieces-of-eight.png',
            'Time Machine' => 'time-machine.png',
            'B.U.G.S.' => 'bugs.png',
            'Dream Police' => 'dream-police.png',
            'Necropolis' => 'necropolis.png',
            "PJ's" => 'pjs.png',
            'Wasteland' => 'wasteland.png',
        ];

        foreach ($coverMap as $title => $filename) {
            $story = Story::where('title', $title)->first();

            if (! $story) {
                $this->command->warn("Story not found: {$title}");

                continue;
            }

            if ($story->getFirstMediaUrl('cover')) {
                $this->command->info("Cover exists: {$title} — skipping.");

                continue;
            }

            $source = database_path('stories/covers/'.$filename);

            if (! File::exists($source)) {
                $this->command->warn("Cover file missing: {$source}");

                continue;
            }

            $story->addMedia($source)
                ->preservingOriginal()
                ->usingFileName('cover-'.Str::slug($title).'.png')
                ->toMediaCollection('cover', 'public');

            $this->command->info("Cover attached: {$title}");
        }

        $this->command->info('Image repair complete.');
    }
}
