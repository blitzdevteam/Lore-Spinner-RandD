<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

final readonly class UpdateUserAction
{
    /**
     * @param array<string, mixed> $payload
     */
    public function handle(User $user, array $payload): User
    {
        $user->update($payload);

        return $user->fresh();
    }
}
