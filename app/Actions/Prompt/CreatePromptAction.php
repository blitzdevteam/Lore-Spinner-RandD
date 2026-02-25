<?php

declare(strict_types=1);

namespace App\Actions\Prompt;


use App\Models\Game;

final readonly class CreatePromptAction
{
    public function handle(Game $game, string $prompt): void
    {
        $currentPrompt = $game->prompts()->latest()->first();

        $currentPrompt->update([
            'prompt' => $prompt,
        ]);
    }
}
