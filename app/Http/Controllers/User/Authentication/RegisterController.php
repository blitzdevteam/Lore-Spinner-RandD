<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Authentication;

use App\Actions\Authentication\CreateAuthenticatableGuardAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Authentication\StoreRegisterRequest;
use App\Models\User;

final class RegisterController extends Controller
{
    public function create()
    {
        return inertia('User/Authentication/Register');
    }

    public function store(
        StoreRegisterRequest $request,
        CreateAuthenticatableGuardAction $createAuthenticatableGuard
    ) {
        /**
         * @var $user User
         */
        $user = $createAuthenticatableGuard->handle('user', ...$request->validated());

        auth('user')->login($user);

        return to_route('user.authentication.verify.index');
    }
}
