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
#[Temperature(0.9)]
#[Timeout(120)]
class OpeningNarrationAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * @throws Throwable
     */
    public function instructions(): Stringable|string
    {
        return view('ai.agents.opening-narration.system-prompt')->render();
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'opening' => $schema
                ->string()
                ->required()
                ->title('Opening Narration')
                ->description('The complete cinematic opening narration as HTML. Uses <strong> for accent highlights, <em> for emphasis, and <br> for line breaks. No wrapping <p> or <div> tags — just inline HTML content.'),
        ];
    }
}
