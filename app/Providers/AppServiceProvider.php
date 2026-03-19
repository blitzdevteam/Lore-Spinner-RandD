<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Story;
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
        $lockDir = storage_path('app/expansion-seeder.running');

        try {
            if (Story::where('title', 'Wasteland')->exists()) {
                return;
            }

            if (is_dir($lockDir)) {
                $age = time() - filemtime($lockDir);

                if ($age < 2700) {
                    return;
                }

                @rmdir($lockDir);
            }

            if (! @mkdir($lockDir)) {
                return;
            }

            $artisan = base_path('artisan');
            $logFile = storage_path('logs/expansion-seeder.log');

            exec("php {$artisan} db:seed --class=ExpansionSeeder --force >> {$logFile} 2>&1 &");
        } catch (Throwable) {
            //
        }
    }
}
