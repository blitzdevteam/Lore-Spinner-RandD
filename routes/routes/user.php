<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User;

Route::prefix('user')->name('user.')->group(function (): void {
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
    });
});
