<?php

declare(strict_types=1);

namespace App\Jobs\Chapter;

use App\Ai\Agents\ChapterExtractorAgent;
use App\Enums\Story\StoryStatusEnum;
use App\Enums\Chapter\ChapterStatusEnum;
use App\Helpers\Story\LineNumberFormatterHelper;
use App\Helpers\Story\NumberedLineExtractorHelper;
use App\Models\Story;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Throwable;

final class ChapterExtractorJob implements ShouldQueue
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
    public function handle(): void
    {
        try {
            $this->story->update([
                'status' => StoryStatusEnum::EXTRACTING_CHAPTERS,
            ]);

            $linedContent = LineNumberFormatterHelper::handle(
                file_get_contents($this->story->getFirstMediaPath('script'))
            );

            $response = new ChapterExtractorAgent()
                ->prompt("Story:\n\n" . $linedContent['content']);

            DB::transaction(function () use ($linedContent, $response) {
                foreach ($response['chapters'] as $chapter) {
                    $this->story->chapters()->create([
                        'title' => $chapter['title'],
                        'position' => $chapter['position'],
                        'teaser' => $chapter['teaser'],
                        'status' => ChapterStatusEnum::AWAITING_WRITER_REVIEW,
                        'content' => NumberedLineExtractorHelper::handle(
                            $linedContent['content'],
                            $chapter['start_line'],
                            $chapter['end_line']
                        ),
                    ]);
                }

                $this->story->update([
                    'status' => StoryStatusEnum::DRAFT,
                ]);
            });
        } catch (Throwable $exception) {
            $this->story->update([
                'status' => StoryStatusEnum::AWAITING_EXTRACTING_CHAPTERS_REQUEST,
            ]);

            throw $exception;
        }
    }
}
