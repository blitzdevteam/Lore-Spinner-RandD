<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Authentication;

use App\Actions\Authentication\LoginAuthenticatableGuardAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Authentication\StoreLoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

final class LoginController extends Controller
{
    public function create(): Response
    {
        return inertia('User/Authentication/Login');
    }

    public function store(
        StoreLoginRequest $request,
        LoginAuthenticatableGuardAction $loginAuthenticatableGuard
    ): RedirectResponse {
        /** @var User|false $user */
        $user = $loginAuthenticatableGuard->handle(
            'user',
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        if ($user === false) {
            return back()
                ->with('error', 'Credentials do not match our records')
                ->onlyInput('email');
        }

        $routeName = match (true) {
            ! $user->hasVerifiedEmail() => 'user.authentication.verify.index',
            ! $user->is_profile_completed => 'user.authentication.complete-profile.edit',
            default => 'user.dashboard.index',
        };

        return to_route($routeName)->with('success', 'You have been logged in successfully');
    }
}
