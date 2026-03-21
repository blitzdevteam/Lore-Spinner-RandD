<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Override;
use Throwable;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        if (! file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (Throwable) {
            //
        }

        $this->attachStoryCovers();
        $this->attachCreatorAvatars();
        $this->attachChapterCovers();
    }

    private function attachCreatorAvatars(): void
    {
        $flag = storage_path('app/avatars-attached.flag');

        if (file_exists($flag)) {
            return;
        }

        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\AvatarRepairSeeder',
                '--force' => true,
            ]);

            file_put_contents($flag, now()->toDateTimeString());
        } catch (Throwable) {
            //
        }
    }

    private function attachChapterCovers(): void
    {
        $flag = storage_path('app/chapter-covers-attached-v2.flag');

        if (file_exists($flag)) {
            return;
        }

        try {
            $slugMap = [
                "Hemingway's War" => 'hemingways-war',
                'High Stakes' => 'high-stakes',
                'Pieces of Eight' => 'pieces-of-eight',
                'Time Machine' => 'time-machine',
                'B.U.G.S.' => 'bugs',
                'Dream Police' => 'dream-police',
                'Necropolis' => 'necropolis',
                "PJ's" => 'pjs',
                'Wasteland' => 'wasteland',
            ];

            foreach ($slugMap as $storyTitle => $slug) {
                $story = \App\Models\Story::where('title', $storyTitle)->first();

                if (! $story) {
                    continue;
                }

                foreach ($story->chapters()->orderBy('position')->get() as $chapter) {
                    if ($chapter->getFirstMedia('cover')) {
                        continue;
                    }

                    $file = database_path("stories/covers/chapters/{$slug}-ch{$chapter->position}.png");

                    if (\Illuminate\Support\Facades\File::exists($file)) {
                        $chapter->addMedia($file)
                            ->preservingOriginal()
                            ->usingFileName("chapter-cover-{$chapter->id}.png")
                            ->toMediaCollection('cover', 'public');
                    }
                }
            }

            file_put_contents($flag, now()->toDateTimeString());
        } catch (Throwable) {
            //
        }
    }

    private function attachStoryCovers(): void
    {
        $flag = storage_path('app/covers-attached-v3.flag');

        if (file_exists($flag)) {
            return;
        }

        try {
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
                $story = \App\Models\Story::where('title', $title)->first();

                if (! $story) {
                    continue;
                }

                $story->clearMediaCollection('cover');

                $source = database_path('stories/covers/'.$filename);

                if (\Illuminate\Support\Facades\File::exists($source)) {
                    $story->addMedia($source)
                        ->preservingOriginal()
                        ->usingFileName('cover-'.\Illuminate\Support\Str::slug($title).'.png')
                        ->toMediaCollection('cover', 'public');
                }
            }

            file_put_contents($flag, now()->toDateTimeString());
        } catch (Throwable) {
            //
        }
    }
}
