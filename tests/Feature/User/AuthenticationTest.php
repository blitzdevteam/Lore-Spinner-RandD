<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia as Assert;

const TEST_EMAIL = 'john@example.com';
const TEST_PASSWORD = 'password';

function createUser(array $payload = []): User
{
    return User::factory()->create($payload);
}

function startUserRegistration(array $payload = []): TestResponse
{
    return test()->post(route('user.authentication.register.store'), $payload);
}

function getRegistrationPayload(array $overrides = []): array
{
    return array_merge([
        'email' => TEST_EMAIL,
        'password' => TEST_PASSWORD,
        'password_confirmation' => TEST_PASSWORD,
    ], $overrides);
}

function getLoginPayload(array $overrides = []): array
{
    return array_merge([
        'email' => TEST_EMAIL,
        'password' => TEST_PASSWORD,
    ], $overrides);
}

describe('registration', function () {
    it('registration page can render', function () {
        $this
            ->get(route('user.authentication.register.create'))
            ->assertOk()
            ->assertInertia(fn(Assert $page) => $page->component('User/Authentication/Register'));
    });

    test('user can not do register action with duplicate email', function () {
        createUser(['email' => TEST_EMAIL]);

        startUserRegistration(getRegistrationPayload())
            ->assertSessionHasErrors('email')
            ->assertRedirectBack();
    });

    test('user can do register action and redirect to email verification route', function () {
        startUserRegistration(getRegistrationPayload())
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('user.authentication.verify.index'));

        $this->assertDatabaseHas('users', [
            'email' => TEST_EMAIL,
            'email_verified_at' => null,
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
        createUser(['email' => TEST_EMAIL, 'password' => bcrypt(TEST_PASSWORD)]);

        $this
            ->post(route('user.authentication.login.store'), getLoginPayload(['password' => 'wrong-password']))
            ->assertSessionHasErrors('email')
            ->assertRedirectBack();
    });

    test('user can do login action', function () {
        createUser(['email' => TEST_EMAIL, 'password' => bcrypt(TEST_PASSWORD)]);

        $this
            ->post(route('user.authentication.login.store'), getLoginPayload())
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('user.dashboard.index'));
    });
});

describe('verification', function () {
    it('verification page can render', function () {
        startUserRegistration(getRegistrationPayload());

        $this
            ->get(route('user.authentication.verify.index'))
            ->assertOk()
            ->assertInertia(fn(Assert $page) => $page->component('User/Authentication/Verify'));
    });

    test('user can do verify action and redirect to user dashboard page', function () {
        Notification::fake();

        startUserRegistration(getRegistrationPayload());
        $user = User::where('email', TEST_EMAIL)->first();

        $verifyUrl = null;
        Notification::assertSentTo($user, VerifyEmail::class, function (VerifyEmail $notification) use ($user, &$verifyUrl) {
            $verifyUrl = $notification->toMail($user)->actionUrl;

            return true;
        });

        $this->get($verifyUrl)
            ->assertRedirect(route('user.dashboard.index'));

        expect($user->fresh()->email_verified_at)->not()->toBeNull();
    });
});
