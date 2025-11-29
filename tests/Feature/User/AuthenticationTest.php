<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;

function createUser($payload = []): User
{
    return User::factory()->create($payload);
}

function startUserRegistration($payload = []): Illuminate\Testing\TestResponse
{
    return test()->post(route('user.authentication.register.store'), $payload);
}

describe('registration', function () {
    it('registration page can render', function () {
        $this
            ->get(route('user.authentication.register.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('User/Authentication/Register'));
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

        startUserRegistration($payload)
            ->assertSessionHasErrors([
                'email',
            ])
            ->assertRedirectBack();
    });

    test('user can do register action and redirect to email verification route', function () {
        $payload = [
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        startUserRegistration($payload)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('user.authentication.verify.index'));

        $this
            ->assertDatabaseHas('users', [
                'email' => $payload['email'],
                'email_verified_at' => null,
            ]);
    });
});

describe('login', function () {
    it('login page can render', function () {
        $this
            ->get(route('user.authentication.login.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('User/Authentication/Login'));
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
                'email',
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

describe('verification', function () {
    it('verification page can render', function () {
        startUserRegistration([
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this
            ->get(route('user.authentication.verify.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('User/Authentication/Verify'));
    });

    test('user can do verify action and redirect to user dashboard page', function () {
        Notification::fake();

        $payload = [
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        startUserRegistration($payload);

        // Get the user and extract the verification URL from the notification
        $user = User::where('email', $payload['email'])->first();

        // Get the verification URL from the notification
        $verifyUrl = null;
        Notification::assertSentTo($user, VerifyEmail::class, function (VerifyEmail $notification) use ($user, &$verifyUrl) {
            $verifyUrl = $notification->toMail($user)->actionUrl;

            return true;
        });

        // Visit the verification URL
        $this->get($verifyUrl)
            ->assertRedirect(route('user.dashboard.index'));

        $user = $user->fresh();

        // Verify the user's email was marked as verified
        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
        ]);
        expect($user->email_verified_at)->not()->toBeNull();
    });
});
