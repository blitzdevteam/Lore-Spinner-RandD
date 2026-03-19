<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

/**
 * Runs the ExpansionSeeder + image generation as a background queue job
 * so it can execute safely after deploy without SSH access.
 *
 * Dispatched from AppServiceProvider::boot() when new creators are missing.
 * ShouldBeUnique prevents duplicate jobs if multiple requests fire before
 * the first job finishes.
 */
final class RunExpansionSeederJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $timeout = 21600;

    public int $tries = 1;

    public int $uniqueFor = 21600;

    public function handle(): void
    {
        $previousQueue = config('queue.default');
        config(['queue.default' => 'sync']);

        try {
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\ExpansionSeeder']);
            Artisan::call('images:generate-missing');
        } finally {
            config(['queue.default' => $previousQueue]);
        }
    }
}
