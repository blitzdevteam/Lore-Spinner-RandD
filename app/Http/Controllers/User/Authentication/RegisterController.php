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

        auth('user')->login($user, remember: true);

        return to_route('user.authentication.account-created')
            ->with('credentials', [
                'email' => $request->string('email')->toString(),
                'password' => $request->string('password')->toString(),
            ]);
    }

    public function accountCreated(): Response|RedirectResponse
    {
        $credentials = session('credentials');

        if (! $credentials) {
            return to_route('user.authentication.complete-profile.edit');
        }

        return inertia('User/Authentication/AccountCreated', [
            'credentials' => $credentials,
        ]);
    }
}
