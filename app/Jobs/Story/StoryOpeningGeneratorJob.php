<?php

declare(strict_types=1);

namespace App\Jobs\Story;

use App\Ai\Agents\OpeningNarrationAgent;
use App\Models\Story;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Responses\StructuredAgentResponse;
use Throwable;

final class StoryOpeningGeneratorJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 300;

    public int $backoff = 60;

    public function __construct(
        private Story $story,
    ) {
        $this->onQueue('opening-generation');
    }

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        try {
            $storyData = $this->story->system_prompt ?? [];

            $firstChapter = $this->story->chapters()->orderBy('position')->first();
            $firstEvent = $firstChapter?->events()->orderBy('position')->first();

            /** @var StructuredAgentResponse $response */
            $response = (new OpeningNarrationAgent)->prompt(
                view('ai.agents.opening-narration.prompt', [
                    'title' => $this->story->title,
                    'teaser' => $this->story->teaser,
                    'characterName' => $storyData['character_name'] ?? null,
                    'toneAndStyle' => $storyData['tone_and_style'] ?? null,
                    'worldRules' => $storyData['world_rules'] ?? [],
                    'firstChapterTitle' => $firstChapter?->title,
                    'firstEventContent' => $firstEvent?->content,
                ])->render()
            );

            $this->story->update([
                'opening' => $response['opening'],
            ]);

            Log::info("StoryOpeningGeneratorJob: Generated opening for story [{$this->story->id}] — {$this->story->title}");
        } catch (Throwable $throwable) {
            Log::error("StoryOpeningGeneratorJob: Failed for story [{$this->story->id}]: {$throwable->getMessage()}");

            throw $throwable;
        }
    }
}
