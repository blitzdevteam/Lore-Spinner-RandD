<?php

declare(strict_types=1);

use App\Ai\Schema\Story\StoryChapterEventExtractorSchema;
use App\Helpers\Story\LineNumberFormatterHelper;
use App\Helpers\Story\NumberedLineExtractorHelper;
use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;

require __DIR__.'/routes/user.php';

Route::get('/', Controllers\IndexController::class)->name('index');

Route::get('test', function () {
    $story = \App\Models\Story::first();

    $linedContent = LineNumberFormatterHelper::handle(
        file_get_contents($story->getFirstMediaPath('script'))
    );

    $response = new \App\Ai\Agents\ChapterExtractorAgent()
        ->prompt("Story:\n\n" . $linedContent['content']);

    $chapters = NumberedLineExtractorHelper::handle(
        $linedContent['content'],
        $response['chapters']
    );

    $story->chapters()->createMany($chapters);
});
