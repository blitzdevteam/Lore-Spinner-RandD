<?php

declare(strict_types=1);

namespace App\Actions\Game;

use App\Models\Game;
use App\Models\Story;
use App\Models\User;

final readonly class CreateGameAction
{
    public function handle(User $user, Story $story): Game
    {
        $firstChapter = $story->chapters()->orderBy('position')->first();

        $firstEvent = $firstChapter->events()
            ->orderBy('position')
            ->first();

        return $user->games()->create([
            'story_id' => $story->id,
            'current_event_id' => $firstEvent->id,
        ]);
    }
}
