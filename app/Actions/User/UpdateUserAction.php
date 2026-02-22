<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

final readonly class UpdateUserAction
{
    /**
     * @param User $user
     * @param array<string, mixed> $payload
     * @return User
     */
    public function handle(User $user, array $payload): User
    {
        $user->update($payload);

        $user->fresh();

        return $user;
    }
}
