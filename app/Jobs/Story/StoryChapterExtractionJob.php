<?php

declare(strict_types=1);

namespace App\Jobs\Story;

use App\Models\Story;
use App\Prism\Schema\Story\StoryChapterExtractionSchema;
use App\Services\Story\StoryChapterExtractorByContentService;
use App\Services\Story\StoryLineNumberedFileService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;

final class StoryChapterExtractionJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 660;

    public int $backoff = 60;

    private Story $story;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Story $story,
    ) {
        $this->story = $story;
    }

    public function handle(): void {
        DB::transaction(function () {
            $tmpPath = StoryLineNumberedFileService::handle(
                file_get_contents($this->story->getFirstMediaPath('script'))
            );

            $response = Prism::structured()
                ->using(Provider::OpenAI, 'gpt-5.2')
                ->usingTemperature(0)
                ->withSystemPrompt(view('ai-prompts.chapter-maker.system-prompt'))
                ->withClientOptions([
                    'connect_timeout' => 10,
                    'timeout' => 600,
                ])
                ->withSchema(StoryChapterExtractionSchema::getSchema())
                ->withPrompt("Story:\n\n" . file_get_contents($tmpPath))
                ->asStructured();

            $chapters = StoryChapterExtractorByContentService::handle(
                file_get_contents($tmpPath),
                $response->structured['chapters']
            );

            $this->story->chapters()->createMany($chapters);

            Storage::delete($tmpPath);
        });
    }
}
