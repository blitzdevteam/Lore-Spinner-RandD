<?php

declare(strict_types=1);

namespace App\Jobs\Event;

use App\Ai\Agents\EventExtractorAgent;
use App\Enums\Chapter\ChapterStatusEnum;
use App\Helpers\LineNumberFormatterHelper;
use App\Helpers\TextRangeExtractorHelper;
use App\Models\Chapter;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
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
            // Mark chapter as extracting events
            $this->chapter->update([
                'status' => ChapterStatusEnum::EXTRACTING_EVENTS,
            ]);

            // Extract events using AI agent with line-numbered content
            $linedContent = LineNumberFormatterHelper::handle($this->chapter->content);
            $response = EventExtractorAgent::make()
                ->prompt(
                    view('ai.agents.event-extractor.prompt', [
                        'length' => $linedContent['length'],
                        'content' => $linedContent['content'],
                    ])->render()
                );

            // Create events sorted by position
            $events = collect($response['events'])
                ->sortBy('position')
                ->map(fn (array $event) => [
                    'position' => $event['position'],
                    'title' => $event['title'],
                    'content' => TextRangeExtractorHelper::handle($this->chapter->content, $event['start'], $event['end']),
                ]);

            // Queue jobs to extract objectives and attributes for each event
            $jobs = $this->chapter->events()
                ->createMany($events->all())
                ->map(fn ($event) => new EventObjectiveAndAttributeExtractor($event->id));

            // Update chapter status to waiting for event preparation
            $this->chapter->update([
                'status' => ChapterStatusEnum::WAITING_FOR_EVENT_PREPARATION,
            ]);

            // Store chapter ID to avoid serialization issues with closures
            // Process batch and update chapter status accordingly
            $chapterId = $this->chapter->id;
            Bus::batch($jobs)
                // After all events have been processed, mark chapter as ready to play
                ->then(function (Batch $batch) use ($chapterId) {
                    Chapter::find($chapterId)?->update([
                        'status' => ChapterStatusEnum::READY_TO_PLAY,
                    ]);
                })
                ->onQueue('event-objective-and-attribute-extraction')
                ->dispatch();
        } catch (Throwable $exception) {
            // Reset chapter status on failure
            $this->chapter->update([
                'status' => ChapterStatusEnum::AWAITING_EXTRACTING_EVENTS_REQUEST,
            ]);

            // Remove any partially created events
            $this->chapter->events()->delete();

            throw $exception;
        }
    }
}
