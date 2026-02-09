<?php

declare(strict_types=1);

namespace App\Jobs\Story;

use App\Ai\Schema\ChapterExtractorSchema;
use App\Models\Story;
use App\Services\Story\StoryAddLineToContentService;
use App\Services\Story\StoryChapterExtractorByContentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
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
            $linedContent = StoryAddLineToContentService::handle(
                file_get_contents($this->story->getFirstMediaPath('script'))
            );

            $response = Prism::structured()
                ->using(Provider::OpenAI, 'gpt-5.2')
                ->usingTemperature(0)
                ->withSystemPrompt(view('ai-prompts.chapter-extraction.system-prompt'))
                ->withClientOptions([
                    'connect_timeout' => 10,
                    'timeout' => 600,
                ])
                ->withSchema(ChapterExtractorSchema::getSchema())
                ->withPrompt("Story:\n\n" . $linedContent['content'])
                ->asStructured();

            $chapters = StoryChapterExtractorByContentService::handle(
                $linedContent['content'],
                $response->structured['chapters']
            );

            $this->story->chapters()->createMany($chapters);
        });
    }
}
