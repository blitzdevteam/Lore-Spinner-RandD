<?php

declare(strict_types=1);

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

require __DIR__.'/routes/user.php';

Route::get('/', Controllers\IndexController::class)->name('index');

Route::resource('creators', Controllers\CreatorController::class)
    ->scoped([
        'creator' => 'username',
    ])
    ->only(['index', 'show']);

Route::resource('stories', Controllers\StoryController::class)
    ->scoped([
        'story' => 'slug',
    ])
    ->only(['index', 'show']);

Route::post('feedback', [Controllers\FeedbackController::class, 'store'])->name('feedback.store');

Route::get('expansion-status', function () {
    $lockFile = storage_path('app/expansion-seeder.lock');
    $logFile = storage_path('logs/expansion-seeder.log');

    $creators = App\Models\Creator::select('first_name', 'last_name', 'email')
        ->withCount('stories')
        ->get()
        ->map(fn ($c) => "{$c->first_name} {$c->last_name} ({$c->email}) — {$c->stories_count} stories");

    $stories = App\Models\Story::select('title', 'status', 'creator_id')
        ->with('creator:id,first_name,last_name')
        ->orderBy('id')
        ->get()
        ->map(fn ($s) => "{$s->title} [{$s->status->value}] — {$s->creator?->first_name} {$s->creator?->last_name}");

    $pdfChecks = [
        'FREEP1 - STORIES/BUGS/BUGS.pdf',
        'FREEP1 - STORIES/CROSSOVERS : DREAM POLICE/Dream Police-2.pdf',
        'FREEP1 - STORIES/NECROPOLIS/Necropolis.pdf',
        "FREEP1 - STORIES/PJ'S/PJ's.pdf",
        'FREEP1 - STORIES/WASTELAND/Wasteland.pdf',
        "Rand Soares - STORIES/HEMINGWAY'S WAR/Hemingway's War 5-22-2025.pdf",
        'Rand Soares - STORIES/HIGH STAKES/High Stakes.pdf',
        'Rand Soares - STORIES/PIECES OF EIGHT/Pieces of Eight.pdf',
        'Rand Soares - STORIES/TIME MACHINE/Time Machine.pdf',
    ];

    $files = collect($pdfChecks)->map(fn ($f) => $f.' — '.(file_exists(database_path('stories/'.$f)) ? 'EXISTS' : 'MISSING'));

    $txtChecks = [
        'BUGS_script.txt', 'DREAM POLICE_script.txt', 'NECROPOLIS_script.txt',
        'PJS_script.txt', 'WASTELAND_script.txt', 'HEMINGWAYS WAR_script.txt',
        'HIGH STAKES_script.txt', 'PIECES OF EIGHT_script.txt', 'TIME MACHINE_script.txt',
    ];

    $txtFiles = collect($txtChecks)->map(fn ($f) => $f.' — '.(file_exists(database_path('stories/'.$f)) ? 'EXISTS' : 'MISSING'));

    return response()->json([
        'lock_file' => file_exists($lockFile) ? file_get_contents($lockFile) : 'NOT FOUND',
        'seeder_log_tail' => file_exists($logFile) ? implode("\n", array_slice(file($logFile), -30)) : 'NO LOG FILE',
        'creators' => $creators,
        'stories' => $stories,
        'pdf_files' => $files,
        'txt_files' => $txtFiles,
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
});
