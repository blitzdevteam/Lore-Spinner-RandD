<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use App\Models\User;
use App\Models\Writer;
use InvalidArgumentException;

final readonly class CreateAuthenticatableGuard
{
    private const array GUARD_MODELS = [
        'user' => User::class,
        'writer' => Writer::class,
    ];

    public function handle(string $guard, string $email, string $password): User|Writer
    {
        if (!array_key_exists($guard, self::GUARD_MODELS)) {
            throw new InvalidArgumentException("Guard `{$guard}` is an invalid guard.");
        }

        $model = self::GUARD_MODELS[$guard];

        return $model::create([
            'email' => $email,
            'password' => $password,

            /**
             * TODO: Verify auth email temporarily and must be removed in the future
             */
            'email_verified_at' => now(),
        ]);
    }
}
