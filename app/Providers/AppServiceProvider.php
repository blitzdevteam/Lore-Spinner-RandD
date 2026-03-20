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

        $this->generateMissingCovers();
    }

    private function generateMissingCovers(): void
    {
        $flag = storage_path('app/covers-generated-v4.flag');

        if (file_exists($flag)) {
            return;
        }

        try {
            file_put_contents($flag, now()->toDateTimeString());

            $artisan = base_path('artisan');
            $logFile = storage_path('logs/image-generation.log');

            exec("php {$artisan} db:seed --class=ImageRepairSeeder --force >> {$logFile} 2>&1 &");
        } catch (Throwable) {
            //
        }
    }
}
