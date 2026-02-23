<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Actions\Game\CreateGameAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Game\StoreGameRequest;
use App\Models\Game;
use App\Models\Story;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

final class GameController extends Controller
{
    public function index(): Response
    {
        return inertia();
    }

    public function show(Game $game): Response
    {
        return inertia('User/Games/Show', [
            'game' => $game->toResource()
        ]);
    }

    public function store(
        #[CurrentUser] User $user,
        StoreGameRequest $request,
        CreateGameAction $createGameAction
    ): RedirectResponse
    {
        $story = Story::find($request->string('story_id')->toString());

        if ($user->games()->whereBelongsTo($story)->exists()) {
            return back()->with('error', 'You have already started this story');
        }

        $game = $createGameAction->handle($user, $story);

        return to_route('user.games.show', $game);
    }
}
