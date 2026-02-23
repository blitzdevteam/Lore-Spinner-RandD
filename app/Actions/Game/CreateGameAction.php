<?php

declare(strict_types=1);

namespace App\Actions\Game;

use App\Models\Game;
use App\Models\Story;
use App\Models\User;
use RuntimeException;

final readonly class CreateGameAction
{
    public function handle(User $user, Story $story): Game
    {
        $firstChapter = $story->chapters()->orderBy('position')->first();

        $game = $user->games()->create([
            'story_id' => $story->id,
            'current_event_id' => $firstChapter->events()
                ->orderBy('position')
                ->first()
                ->id
        ]);

        $game->prompts()->create([
            'event_id' => $firstChapter->events()
                ->orderBy('position')
                ->limit(1)
                ->value('id'),
            'response' => $game->story()->value('opening'),
            'choices' => ['Begin your adventure']
        ]);

        return $game;
    }
}
