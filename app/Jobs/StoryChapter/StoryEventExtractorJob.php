<?php

namespace App\Jobs\StoryChapter;

use App\Jobs\ProcessSingleSceneJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;

class StoryEventExtractorJob implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $chapterId = 1,
        public int $sceneCount = 24
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Read and parse the screenplay
        $screenplay = File::get(storage_path('app/private/story.txt'));
        $screenplay = preg_replace('/^\x{FEFF}/u', '', $screenplay);

        $scenes = preg_split('/(?=(?:EXT\.|INT\.))/m', (string) $screenplay, -1, PREG_SPLIT_NO_EMPTY);

        // Dispatch a separate job for each scene with its index
        collect($scenes)
            ->take($this->sceneCount)
            ->each(function ($scene, $index): void {
                ProcessSingleSceneJob::dispatch(
                    scene: $scene,
                    sceneIndex: $index,
                    chapterId: $this->chapterId
                );
            });
    }
}
