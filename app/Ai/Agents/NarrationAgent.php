<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

#[Model('gpt-5.2')]
#[Temperature(0.85)]
#[Timeout(60)]
class NarrationAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Create a new narration agent instance.
     *
     * @param  string  $customInstructions  Runtime-rendered system prompt with story data + event context.
     */
    public function __construct(
        private string $customInstructions,
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return $this->customInstructions;
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'response' => $schema
                ->string()
                ->required()
                ->title('Response')
                ->description('Cinematic narrative as HTML. Use <p> tags for paragraphs, <em> for emphasis, <strong> for impactful moments. Immersive, atmospheric, second-person. 2-4 paragraphs.'),
            'choices' => $schema
                ->array()
                ->required()
                ->title('Choices')
                ->description('Exactly 3 short actionable choices. Each starts with a strong verb. Ordered by forward momentum: most forward, moderate, least forward (but still changes state).')
                ->items(
                    $schema
                        ->string()
                        ->required()
                        ->title('Choice')
                        ->description('A single concrete, actionable choice starting with a strong verb.')
                ),
        ];
    }
}
