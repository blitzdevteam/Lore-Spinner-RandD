<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use App\Models\User;
use App\Models\Writer;
use InvalidArgumentException;

final readonly class LoginAuthenticatableGuard
{
    private const array GUARD_MODELS = [
        'user' => User::class,
        'writer' => Writer::class,
    ];

    public function handle(string $guard, string $email, string $password): User|Writer|false
    {
        if (!array_key_exists($guard, self::GUARD_MODELS)) {
            throw new InvalidArgumentException("Guard `{$guard}` is an invalid guard.");
        }

        $check = auth($guard)->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if ($check) {
            return auth($guard)->user();
        }

        return false;
    }
}
