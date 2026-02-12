<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;
use Throwable;

#[Model('gpt-5.2')]
#[Timeout(120)]
class EventObjectiveAndAttributesExtractor implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     * @throws Throwable
     */
    public function instructions(): Stringable|string
    {
        return view('ai.agents.event-objective-and-attribute-extractor.system-prompt')->render();
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema
                ->string()
                ->title('Event Title')
                ->description('The exact title or name of the event being analyzed.')
                ->required(),
            'objective' => $schema
                ->string()
                ->title('Objective')
                ->description('The primary objective or goal of this event. A concise statement describing what the event aims to accomplish in the narrative.')
                ->required(),
            'attributes' => $schema
                ->array()
                ->title('Attributes')
                ->description('List of key attributes, themes, or characteristics associated with this event.')
                ->items(
                    $schema
                        ->string()
                        ->title('Attribute')
                        ->description('A single attribute or theme associated with the event.')
                )
                ->required(),
        ];
    }
}
