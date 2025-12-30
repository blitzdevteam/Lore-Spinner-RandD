<?php

declare(strict_types=1);

use App\Http\Controllers;
use App\Prism\Schema\Story\StoryChapterExtractionSchema;
use App\Services\Story\StoryChapterExtractorByContentService;
use App\Services\Story\StoryLineNumberedFileService;
use Illuminate\Support\Facades\Route;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;

require __DIR__.'/routes/user.php';

Route::get('/', Controllers\IndexController::class)->name('index');
