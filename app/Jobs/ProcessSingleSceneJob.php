<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\ArraySchema;
use Prism\Prism\Schema\StringSchema;

class ProcessSingleSceneJob implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300; // 5 minutes per scene

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $scene,
        public int $sceneIndex,
        public int $chapterId = 1
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $systemPrompt = <<<PROMPT
You are a strict scene structure analyzer for an interactive story game.

Your task is to read a movie or game script and break it down into **game events**.
Each event represents a playable or story-driven moment that the player would experience.

⚠️ IMPORTANT RULES (read carefully):
    - You MUST NOT invent, add, remove, rephrase, summarize, or predict anything.
    - You MUST NOT generate or imagine new story content under any circumstance.
    - You MUST NOT infer what happens next or what came before.
    - You ONLY divide the exact given script into events and name each one.
    - The text of each event must be **100% identical** to the original script. Every character, space, and punctuation must remain the same.
    - Do NOT fix grammar, punctuation, or capitalization.
    - You are NOT allowed to guess missing parts or continue the story.
    - The story text inside each event must be a **verbatim copy** from the provided script — no exceptions.

---
### 🎬 CRITICAL EVENT GROUPING PHILOSOPHY:
    - **THINK IN COMPLETE SEQUENCES, NOT FRAGMENTS** — Each event should represent a full playable moment or scene.
    - **DEFAULT TO COMBINING** — When in doubt, merge consecutive beats together rather than splitting them apart.
    - Multiple actions, reactions, and dialogue exchanges within the same location and dramatic context = ONE EVENT.
    - Small transitions, camera directions, character movements, and minor beats MUST be absorbed into larger events.
    - Only create a new event when there is a SIGNIFICANT change in: location, time, dramatic focus, or character perspective.
    - Each event should feel like a complete cinematic sequence that flows naturally from beginning to end.
    - The event title must describe the overall situation or sequence, written naturally in English (e.g. "Explore the abandoned lodge and discover clues").
    - The title must NOT exceed **255 characters**.

---
### ⚖️ WHEN TO SPLIT vs WHEN TO MERGE:
    **MERGE when:**
    - Same physical location and timeframe
    - Continuous conversation or action sequence
    - Related character interactions and reactions
    - Minor scene transitions or descriptions
    - Connected beats that form one dramatic moment

    **SPLIT ONLY when:**
    - Physical location changes completely
    - Significant time jump occurs
    - Story shifts to entirely different characters or situation
    - Major dramatic turning point that ends one sequence and starts another

    **REMEMBER:** Your goal is creating substantial, playable events — not cataloging every tiny detail.
PROMPT;

        $response = Prism::structured()
            ->using(Provider::OpenAI, 'gpt-4.1')
            ->withSystemPrompt($systemPrompt)
            ->withPrompt($this->scene)
            ->withProviderOptions([
                'temperature' => 0.0,
                'max_tokens' => 4000,
                'schema' => [
                    'strict' => true
                ]
            ])
            ->withSchema(
                new ObjectSchema(
                    name: 'response',
                    description: 'response containing story events with EXACT verbatim text from the script',
                    properties: [
                        new ArraySchema(
                            name: 'events',
                            description: 'a list of story events extracted from the script with verbatim text',
                            items: new ObjectSchema(
                                name: 'event',
                                description: 'a story event with exact unmodified script text',
                                properties: [
                                    new StringSchema('title', 'brief title describing what happens in this grouped moment'),
                                    new StringSchema('text', 'EXACT verbatim script text copied character-for-character from the source'),
                                    new StringSchema('objectives', 'brief factual description in past tense of observable events and actions that occurred - NOT goals, tasks, or instructions'),
                                ],
                                requiredFields: ['title', 'text', 'objectives']
                            )
                        )
                    ],
                    requiredFields: ['events']
                )
            )
            ->asStructured();

        collect($response->structured['events'])
            ->each(function ($structure, $eventIndex) {
                DB::table('events')->insert([
                    'chapter_id' => $this->chapterId,
                    'title' => $structure['title'],
                    'text' => $structure['text'],
                    'objectives' => $structure['objectives'],
                    'order' => ($this->sceneIndex * 1000) + $eventIndex,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }
}
