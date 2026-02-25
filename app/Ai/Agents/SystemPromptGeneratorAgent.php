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
use Throwable;

#[Model('gpt-5.2')]
#[Temperature(0.4)]
#[Timeout(180)]
class SystemPromptGeneratorAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     *
     * @throws Throwable
     */
    public function instructions(): Stringable|string
    {
        return view('ai.agents.system-prompt-generator.system-prompt')->render();
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'playable_character_name' => $schema
                ->string()
                ->required()
                ->title('Playable Character Name')
                ->description('The name of the main playable character (protagonist controlled by the player). UPPERCASE. Example: "NORA", "LUCAS".'),
            'world_rules' => $schema
                ->array()
                ->required()
                ->title('Global World Rules')
                ->description('Array of world rules extracted from the story. Each rule should be a single sentence describing a fundamental rule of the story world. Prefix each with [GRn] format. Between 5 and 25 rules.')
                ->items(
                    $schema
                        ->string()
                        ->required()
                        ->title('World Rule')
                        ->description('A single world rule in the format: "[GRn] Rule description." Must be observable, enforceable, and spoiler-free.')
                ),
            'tone_and_style' => $schema
                ->string()
                ->required()
                ->title('Tone and Style')
                ->description('A short description (1-3 sentences) of the story\'s dominant tone, atmosphere, and prose style. Example: "Dark psychological horror with slow-burn tension. Clinical language contrasting with visceral emotional undercurrents."'),
        ];
    }
}
