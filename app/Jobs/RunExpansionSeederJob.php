<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Throwable;

final class RunExpansionSeederJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $timeout = 21600;

    public int $tries = 2;

    public int $uniqueFor = 300;

    public int $backoff = 60;

    public function handle(): void
    {
        Log::info('RunExpansionSeederJob: starting expansion seeder...');

        $previousQueue = config('queue.default');
        config(['queue.default' => 'sync']);

        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ExpansionSeeder',
                '--force' => true,
            ]);

            Log::info('RunExpansionSeederJob: seeder complete, generating images...');

            Artisan::call('images:generate-missing');

            Log::info('RunExpansionSeederJob: all done.');
        } catch (Throwable $e) {
            Log::error('RunExpansionSeederJob failed: '.$e->getMessage());

            throw $e;
        } finally {
            config(['queue.default' => $previousQueue]);
        }
    }
}
