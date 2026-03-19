<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Override;
use Throwable;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        if (! file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        $this->runExpansionSeederOnce();
    }

    private function runExpansionSeederOnce(): void
    {
        $lockFile = storage_path('app/expansion-seeder.lock');

        try {
            $storiesExist = \App\Models\Story::where('title', 'B.U.G.S.')->exists();

            if ($storiesExist) {
                if (! file_exists($lockFile)) {
                    file_put_contents($lockFile, 'completed');
                }

                return;
            }

            if (file_exists($lockFile)) {
                $age = time() - filemtime($lockFile);
                if ($age < 7200) {
                    return;
                }
                unlink($lockFile);
            }

            file_put_contents($lockFile, 'started at '.now()->toDateTimeString());

            $artisan = base_path('artisan');
            $logFile = storage_path('logs/expansion-seeder.log');

            exec("php {$artisan} db:seed --class=ExpansionSeeder --force >> {$logFile} 2>&1 &");
        } catch (Throwable) {
            //
        }
    }
}
