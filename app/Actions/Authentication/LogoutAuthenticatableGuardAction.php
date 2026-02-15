<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use App\Models\User;
use App\Models\Creator;
use Illuminate\Http\Request;
use InvalidArgumentException;

final readonly class LogoutAuthenticatableGuardAction
{
    private const array GUARD_MODELS = [
        'user' => User::class,
        'creator' => Creator::class,
    ];

    public function handle(string $guard, Request $request): bool
    {
        if (! array_key_exists($guard, self::GUARD_MODELS)) {
            throw new InvalidArgumentException(sprintf('Guard `%s` is an invalid guard.', $guard));
        }

        auth($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return true;
    }
}
