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
            'objective' => $schema
                ->string()
                ->title('Objective')
                ->description('Observable state change at the END of the event compared to the BEGINNING. Structure: "[Subject] + [observable state change]." Must describe what new condition exists, what object is altered, what character status changed, what access changed, or what environmental condition changed. Use "No material state change occurs." only when strictly true.')
                ->required(),
            'attributes' => $schema
                ->array()
                ->title('Attributes')
                ->description('Story-state continuity facts from the 6-category checklist. Each array item is one category with format "Category: fact1 | fact2 | fact3". Categories: (1) Location, (2) Characters physically present, (3) Persistent physical conditions, (4) Objects, (5) Environmental conditions, (6) Factual dialogue. Omit categories with no data. Use pipe "|" to separate multiple facts within a category.')
                ->items(
                    $schema
                        ->string()
                        ->title('Attribute')
                        ->description('One category line in format "Category: fact1 | fact2 | fact3". Example: "Objects: sealed envelope on desk | photograph of father | coat and keys"')
                        ->required()
                )
                ->required(),
        ];
    }
}
