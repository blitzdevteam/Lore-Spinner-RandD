<?php

declare(strict_types=1);

use App\Ai\Schema\Story\StoryChapterEventExtractorSchema;
use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

require __DIR__.'/routes/user.php';

Route::get('/', Controllers\IndexController::class)->name('index');
