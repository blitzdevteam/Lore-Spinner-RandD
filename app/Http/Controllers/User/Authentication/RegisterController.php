<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Authentication;

use App\Actions\Authentication\CreateAuthenticatableGuardAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Authentication\StoreRegisterRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

final class RegisterController extends Controller
{
    public function create(): Response
    {
        return inertia('User/Authentication/Register');
    }

    public function store(
        StoreRegisterRequest $request,
        CreateAuthenticatableGuardAction $createAuthenticatableGuard
    ): RedirectResponse {
        $user = $createAuthenticatableGuard->handle(
            'user',
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        auth('user')->login($user);

        return to_route('user.authentication.verify.index');
    }
}
