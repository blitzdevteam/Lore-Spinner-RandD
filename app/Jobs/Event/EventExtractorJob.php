<?php

declare(strict_types=1);

namespace App\Jobs\Event;

use App\Ai\Agents\EventExtractorAgent;
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
        $response = EventExtractorAgent::make()
            ->prompt($this->chapter->content);

        foreach ($response['events'] as $event) {
            $this->chapter->events()->create([
                'title' => $event['title'],
                'position' => $event['position'],
                'text' => $event['']
            ]);
        }
    }
}
