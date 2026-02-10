<?php

declare(strict_types=1);

namespace App\Jobs\Story;

use App\Ai\Agents\ChapterExtractorAgent;
use App\Helpers\Story\LineNumberFormatterHelper;
use App\Helpers\Story\NumberedLineExtractorHelper;
use App\Models\Story;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Throwable;

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
        $this->onQueue('chapter-extraction');

        $this->story = $story;
    }

    /**
     * @throws Throwable
     */
    public function handle(): void {
        DB::transaction(function () {
            $linedContent = LineNumberFormatterHelper::handle(
                file_get_contents($this->story->getFirstMediaPath('script'))
            );

            $response = new ChapterExtractorAgent()
                ->prompt("Story:\n\n" . $linedContent['content']);

            $chapters = NumberedLineExtractorHelper::handle(
                $linedContent['content'],
                $response['chapters']
            );

            $this->story->chapters()->createMany($chapters);
        });
    }
}
