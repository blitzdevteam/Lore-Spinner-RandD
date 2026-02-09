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
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'events' => $schema->array()
                ->title('events')
                ->description('Ordered list of extracted events in reading order. Each event must reference start/end coordinates within the provided #line# marked excerpt (excluding the marker from char indexing).')
                ->items(
                    $schema
                        ->object([
                            'index' => $schema
                                ->number()
                                ->title('index')
                                ->description('1-based sequential event index in this extraction output (1, 2, 3...).')
                                ->required(),
                            'title' => $schema
                                ->string()
                                ->title('title')
                                ->description('Concise event title (3–10 words). Must not add details not present in the excerpt.')
                                ->required(),
                            'start' => $schema
                                ->object([
                                    'line' => $schema
                                        ->number()
                                        ->title('line')
                                        ->description('Start line number (must exist in the provided excerpt).'),
                                    'char' => $schema
                                        ->number()
                                        ->title('char')
                                        ->description('Start character offset (0-based) within the line text (excluding the line marker).'),
                                ])
                                ->title('start')
                                ->description('Start coordinate for the event. line is the numeric line marker; char is 0-based index within the line text EXCLUDING the "#<LINE># " marker.')
                                ->required(),
                            'end' => $schema
                                ->object([
                                'line' => $schema
                                    ->number()
                                    ->title('line')
                                    ->description('End line number (must exist in the provided excerpt).'),
                                'char' => $schema
                                    ->number()
                                    ->title('char')
                                    ->description('End character offset (0-based, EXCLUSIVE) within the line text (excluding the line marker).'),
                            ])
                                ->title('end')
                                ->description('End coordinate for the event. end_char is EXCLUSIVE (slice up to but not including this char). May end mid-line.')
                                ->required(),
                        ])
                        ->title('event')
                        ->description('A single actionable event represented by a title and coordinate boundaries (no evidence text).')
                        ->required()
                )
                ->required()
        ];
    }
}
