<?php

declare(strict_types=1);

namespace App\Jobs\Event;

use App\Ai\Agents\EventExtractorAgent;
use App\Enums\Chapter\ChapterStatusEnum;
use App\Helpers\LineNumberFormatterHelper;
use App\Helpers\TextRangeExtractorHelper;
use App\Models\Chapter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

final class EventExtractorJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 660;

    public int $backoff = 60;

    private Chapter $chapter;

    /**
     * Create a new job instance.
     */
    public function __construct(
        Chapter $chapter,
    ) {
        $this->onQueue('event-extraction');

        $this->chapter = $chapter;
    }

    /**
     * @throws Throwable
     */
    public function handle(): void {
        try {
            $this->chapter->update([
                'status' => ChapterStatusEnum::EXTRACTING_EVENTS,
            ]);

            $linedContent = LineNumberFormatterHelper::handle($this->chapter->content);

            $response = EventExtractorAgent::make()
                ->prompt(implode("\n", [
                    'Line Lengths:',
                    '',
                    $linedContent['length'],
                    '',
                    '',
                    'Chapter Content:',
                    '',
                    $linedContent['content']
                ]));

            $events = array_map(function (array $event) {
                return [
                    'position' => $event['position'],
                    'title' => $event['title'],
                    'content' => TextRangeExtractorHelper::handle($this->chapter->content, $event['start'], $event['end']),
                ];
            }, $response['events']);

            $this->chapter->events()->createMany($events);
        } catch (Throwable $exception) {
            $this->chapter->update([
                'status' => ChapterStatusEnum::AWAITING_EXTRACTING_EVENTS_REQUEST,
            ]);

            throw $exception;
        }
    }
}
