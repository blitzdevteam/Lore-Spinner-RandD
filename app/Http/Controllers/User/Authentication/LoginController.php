<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Authentication;

use App\Actions\Authentication\LoginAuthenticatableGuard;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Authentication\StoreLoginRequest;
use App\Models\User;

final class LoginController extends Controller
{
    public function create()
    {
        return inertia('User/Authentication/Login');
    }

    public function store(
        StoreLoginRequest $request,
        LoginAuthenticatableGuard $loginAuthenticatableGuard
    ) {
        /**
         * @var User|false $check
         */
        $check = $loginAuthenticatableGuard->handle('user', ...$request->validated());

        if ($check === false) {
            return back()
                ->withErrors([
                    'email' => 'Credentials do not match our records.',
                ])
                ->onlyInput('email');
        }

        return to_route('user.dashboard.index');
    }
}
