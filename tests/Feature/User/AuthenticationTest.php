<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

describe('registration', function () {
    it('registration page can render', function () {
        $this
            ->get(route('user.authentication.register.create'))
            ->assertOk()
            ->assertInertia(fn(Assert $page) => $page->component('User/Authentication/Register'));
    });

    test('user can not do register action with duplicate email', function () {
        $payload = [
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        User::factory()->create([
            'email' => $payload['email'],
        ]);

        $this
            ->post(route('user.authentication.register.store'), $payload)
            ->assertSessionHasErrors([
                'email'
            ])
            ->assertRedirectBack();
    });

    test('user can do register action', function () {
        $payload = [
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this
            ->post(route('user.authentication.register.store'), $payload)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('user.dashboard.index'));

        $this
            ->assertDatabaseHas('users', [
                'email' => $payload['email']
            ]);
    });
});
