<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use App\Models\User;
use App\Models\Writer;
use Illuminate\Http\Request;
use InvalidArgumentException;

final readonly class LogoutAuthenticatableGuardAction
{
    private const array GUARD_MODELS = [
        'user' => User::class,
        'writer' => Writer::class,
    ];

    public function handle(string $guard, Request $request): bool
    {
        if (! array_key_exists($guard, self::GUARD_MODELS)) {
            throw new InvalidArgumentException("Guard `{$guard}` is an invalid guard.");
        }

        auth($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return true;
    }
}
