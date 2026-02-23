<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;
use Throwable;

#[Model('gpt-5.2')]
#[Timeout(120)]
class EventExtractorAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     * @throws Throwable
     */
    public function instructions(): Stringable|string
    {
        return view('ai.agents.event-extractor.system-prompt')->render();
    }

    /**
     * Get the agent's structured output schema definition.
     *
     * @return array{
     *   events: list<array{
     *     position: int,
     *     title: string,
     *     start: array{start: string, end: string},
     *     end: array{start: string, end: string}
     *   }>
     * }
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'events' => $schema->array()
                ->title('Events')
                ->description('Ordered list of extracted events in reading order. Each event represents a continuous playable sequence. Split only on clear shifts in: physical location, time, primary activity, or narrative perspective.')
                ->items(
                    $schema
                        ->object([
                            'position' => $schema
                                ->number()
                                ->title('Position')
                                ->description('1-based sequential event index in this extraction output (1, 2, 3...).')
                                ->required(),
                            'title' => $schema
                                ->string()
                                ->title('Title')
                                ->description('Observable activity description under 255 characters. Must avoid interpretation, metaphor, or thematic framing. Do not include character names unless present in excerpt.')
                                ->required(),
                            'start' => $schema
                                ->object([
                                    'line' => $schema
                                        ->number()
                                        ->title('Line')
                                        ->description('Start line number (must exist in the provided excerpt).')
                                        ->required(),
                                    'char' => $schema
                                        ->number()
                                        ->title('Char')
                                        ->description('Start character offset (0-based) within the line text (excluding the "#<LINE># " marker).')
                                        ->required(),
                                ])
                                ->title('Start')
                                ->description('Start coordinate for the event. Coordinates allow verbatim extraction of Event Text from source script.')
                                ->withoutAdditionalProperties()
                                ->required(),
                            'end' => $schema
                                ->object([
                                    'line' => $schema
                                        ->number()
                                        ->title('Line')
                                        ->description('End line number (must exist in the provided excerpt).')
                                        ->required(),
                                    'char' => $schema
                                        ->number()
                                        ->title('Char')
                                        ->description('End character offset (0-based, EXCLUSIVE) within the line text (excluding the "#<LINE># " marker).')
                                        ->required(),
                                ])
                                ->title('End')
                                ->description('End coordinate for the event. end_char is EXCLUSIVE (slice up to but not including this char). Coordinates allow verbatim extraction.')
                                ->withoutAdditionalProperties()
                                ->required(),
                        ])
                        ->title('Event')
                        ->description('A single playable event with title and coordinate boundaries. Each event should represent a FULL playable moment, not a fragment.')
                        ->withoutAdditionalProperties()
                        ->required()
                )
                ->required()
        ];
    }
}
