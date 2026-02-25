<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Game;

use App\Ai\Agents\NarrationAgent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Game\Prompt\StorePromptRequest;
use App\Models\Chapter;
use App\Models\Event;
use App\Models\Game;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Laravel\Ai\Responses\StructuredAgentResponse;
use Throwable;

final class PromptController extends Controller
{
    public function store(
        #[CurrentUser] User $user,
        Game $game,
        StorePromptRequest $request,
    ): RedirectResponse {
        $prompt = $request->string('prompt')->toString();
        $isContinue = $prompt === '__continue__';

        $game->prompts()->latest()->first()?->update([
            'prompt' => $isContinue ? '__continue__' : $prompt,
        ]);

        // Find the next event
        $currentEvent = $game->currentEvent;

        $nextEvent = Event::query()
            ->where('chapter_id', $currentEvent->chapter_id)
            ->where('position', '>', $currentEvent->position)
            ->orderBy('position')
            ->first();

        if (! $nextEvent) {
            $nextChapter = Chapter::query()
                ->where('story_id', $game->story_id)
                ->where('position', '>', $currentEvent->chapter->position)
                ->orderBy('position')
                ->first();

            $nextEvent = $nextChapter?->events()->orderBy('position')->first();
        }

        if ($nextEvent) {
            $game->update(['current_event_id' => $nextEvent->id]);

            // Build conversation history for context
            $conversationHistory = $this->buildConversationHistory($game);

            // Render system prompt at runtime with story data + event context
            $systemPrompt = $this->renderSystemPrompt(
                story: $game->story,
                currentEvent: $nextEvent,
            );

            // Generate AI narration + choices
            $aiResult = $this->generateNarration(
                systemPrompt: $systemPrompt,
                conversationHistory: $conversationHistory,
                playerAction: $isContinue ? 'Continue forward' : $prompt,
            );

            $game->prompts()->create([
                'event_id' => $nextEvent->id,
                'response' => $aiResult['response'],
                'choices' => $aiResult['choices'],
            ]);
        }

        return back();
    }

    /**
     * Render the full system prompt at runtime with story data + event context.
     */
    private function renderSystemPrompt(
        \App\Models\Story $story,
        Event $currentEvent,
    ): string {
        $storyData = $story->system_prompt ?? [];

        return view('ai.agents.narration.system-prompt', [
            'characterName' => $storyData['character_name'] ?? null,
            'worldRules' => $storyData['world_rules'] ?? [],
            'toneAndStyle' => $storyData['tone_and_style'] ?? null,
            'previousEvents' => $this->getPreviousEvents($currentEvent, 3),
            'currentEvent' => [
                'position' => $currentEvent->position,
                'title' => $currentEvent->title,
                'content' => $currentEvent->content,
                'objectives' => $currentEvent->objectives,
                'attributes' => $currentEvent->attributes,
            ],
            'nextEvents' => $this->getNextEvents($currentEvent, 2),
        ])->render();
    }

    /**
     * Build conversation history from previous prompts.
     *
     * @return array<int, array{role: string, text: string}>
     */
    private function buildConversationHistory(Game $game): array
    {
        $history = [];

        $prompts = $game->prompts()
            ->oldest()
            ->limit(6) // Keep context window reasonable
            ->get();

        foreach ($prompts as $p) {
            if ($p->response) {
                $history[] = ['role' => 'narrator', 'text' => strip_tags($p->response)];
            }
            if ($p->prompt && $p->prompt !== '__continue__') {
                $history[] = ['role' => 'player', 'text' => $p->prompt];
            } elseif ($p->prompt === '__continue__') {
                $history[] = ['role' => 'player', 'text' => 'Continue forward'];
            }
        }

        return $history;
    }

    /**
     * Get previous events for continuity context.
     *
     * @return array<int, array{position: int, title: string, objectives: string|null}>
     */
    private function getPreviousEvents(Event $currentEvent, int $take = 3): array
    {
        return Event::query()
            ->where('chapter_id', $currentEvent->chapter_id)
            ->where('position', '<', $currentEvent->position)
            ->orderByDesc('position')
            ->take($take)
            ->get()
            ->reverse()
            ->map(fn (Event $event): array => [
                'position' => $event->position,
                'title' => $event->title,
                'objectives' => $event->objectives,
            ])
            ->values()
            ->all();
    }

    /**
     * Get next events for pacing awareness (titles only — no spoilers).
     *
     * @return array<int, array{position: int, title: string}>
     */
    private function getNextEvents(Event $currentEvent, int $take = 2): array
    {
        return Event::query()
            ->where('chapter_id', $currentEvent->chapter_id)
            ->where('position', '>', $currentEvent->position)
            ->orderBy('position')
            ->take($take)
            ->get()
            ->map(fn (Event $event): array => [
                'position' => $event->position,
                'title' => $event->title,
            ])
            ->all();
    }

    /**
     * Generate AI narration and choices for the current event.
     *
     * @param  array<int, array{role: string, text: string}>  $conversationHistory
     * @return array{response: string, choices: string[]}
     */
    private function generateNarration(
        string $systemPrompt,
        array $conversationHistory,
        string $playerAction,
    ): array {
        try {
            /** @var StructuredAgentResponse $response */
            $response = NarrationAgent::make(customInstructions: $systemPrompt)
                ->prompt(
                    view('ai.agents.narration.prompt', [
                        'conversationHistory' => $conversationHistory,
                        'playerAction' => $playerAction,
                    ])->render()
                );

            return [
                'response' => $response['response'] ?? '',
                'choices' => $response['choices'] ?? ['Continue forward', 'Investigate your surroundings', 'Take a moment to reflect'],
            ];
        } catch (Throwable) {
            return [
                'response' => '<p>The scene unfolds before you...</p>',
                'choices' => ['Continue forward', 'Investigate your surroundings', 'Take a moment to reflect'],
            ];
        }
    }
}
