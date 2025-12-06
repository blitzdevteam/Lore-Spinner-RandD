<?php

declare(strict_types=1);

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('serializes with expected keys', function () {
    expect(array_keys($this->user->fresh()->toArray()))->toEqualCanonicalizing([
        'id',
        'first_name',
        'last_name',
        'full_name',
        'gender',
        'nickname',
        'username',
        'email',
        'avatar',
        'bio',
        'media'
    ]);
});
