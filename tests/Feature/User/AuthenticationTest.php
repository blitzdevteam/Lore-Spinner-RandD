<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function createUser($payload = []): User
{
    return User::factory()->create($payload);
}

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

        createUser([
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

describe('login', function () {
    it('login page can render', function () {
        $this
            ->get(route('user.authentication.login.create'))
            ->assertOk()
            ->assertInertia(fn(Assert $page) => $page->component('User/Authentication/Login'));
    });

    test('user can not do login action with wrong email or password', function () {
        $payload = [
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        createUser($payload);

        $this
            ->post(route('user.authentication.login.store'), [
                ...$payload,
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrors([
                'email'
            ])
            ->assertRedirectBack();
    });


    test('user can do login action', function () {
        $payload = [
            'email' => 'john@example.com',
            'password' => 'password',
        ];

        createUser($payload);

        $this
            ->post(route('user.authentication.login.store'), $payload)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('user.dashboard.index'));
    });
});
