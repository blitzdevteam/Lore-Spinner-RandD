<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use App\Models\User;
use App\Models\Writer;
use Illuminate\Auth\Events\Registered;
use InvalidArgumentException;

final readonly class CreateAuthenticatableGuardAction
{
    private const array GUARD_MODELS = [
        'user' => User::class,
        'writer' => Writer::class,
    ];

    public function handle(string $guard, string $email, string $password): User|Writer
    {
        if (! array_key_exists($guard, self::GUARD_MODELS)) {
            throw new InvalidArgumentException("Guard `{$guard}` is an invalid guard.");
        }

        $model = self::GUARD_MODELS[$guard];
        $model = $model::create([
            'email' => $email,
            'password' => $password,
        ]);

        event(new Registered($model));

        return $model;
    }
}
