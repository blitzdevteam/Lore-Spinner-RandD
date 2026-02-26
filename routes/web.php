<?php

declare(strict_types=1);

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

require __DIR__.'/routes/user.php';

Route::get('/', Controllers\IndexController::class)->name('index');

Route::resource('creators', Controllers\CreatorController::class)
    ->scoped([
        'creator' => 'username'
    ])
    ->only(['index', 'show']);

Route::resource('stories', Controllers\StoryController::class)
    ->scoped([
        'story' => 'slug'
    ])
    ->only(['index', 'show']);

Route::post('feedback', [Controllers\FeedbackController::class, 'store'])->name('feedback.store');
