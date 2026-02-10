<?php

declare(strict_types=1);

namespace App\Jobs\StoryChapter;

use App\Ai\Agents\ChapterExtractorAgent;
use App\Ai\Agents\EventExtractorAgent;
use App\Enums\Story\StoryStatusEnum;
use App\Helpers\Story\LineNumberFormatterHelper;
use App\Helpers\Story\NumberedLineExtractorHelper;
use App\Models\Story;
use App\Models\StoryChapter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Throwable;

final class EventExtractorJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 660;

    public int $backoff = 60;

    private StoryChapter $chapter;

    /**
     * Create a new job instance.
     */
    public function __construct(
        StoryChapter $chapter,
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
