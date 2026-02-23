<?php

declare(strict_types=1);

use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function (): void {
    // Authentication
    Route::prefix('authentication')->name('authentication.')->group(function () {
        Route::middleware('guest')->group(function () {
            Route::prefix('register')->name('register.')->controller(User\Authentication\RegisterController::class)->group(function () {
                Route::get('/', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });
            Route::prefix('login')->name('login.')->controller(User\Authentication\LoginController::class)->group(function () {
                Route::get('/', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });
        });
        Route::middleware('auth:user')->group(function () {
            Route::prefix('verify')->controller(User\Authentication\VerifyController::class)->name('verify.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('resend', 'resend')->name('resend');
                Route::get('confirm/{id}/{hash}', 'confirm')->middleware(['signed', 'throttle:6,1'])->name('confirm');
            });
            Route::prefix('complete-profile')
                ->middleware([
                    'guard.profile-is-incompleted',
                    'verified:user.authentication.verify.index',
                ])
                ->singleton('complete-profile', User\Authentication\CompleteProfileController::class)
                ->except('show');
            Route::delete('logout', [User\Authentication\LogoutController::class, 'destroy'])->name('logout');
        });
    });

    // Dashboard
    Route::middleware([
        'auth:user',
        'verified:user.authentication.verify.index',
        'guard.profile-is-completed',
    ])
        ->group(function () {
            Route::get('dashboard', User\DashboardController::class)->name('dashboard.index');

            Route::resource('games', User\GameController::class)
                ->only(['index', 'show', 'store']);
        });
});
