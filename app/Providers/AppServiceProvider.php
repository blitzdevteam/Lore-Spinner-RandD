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

        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (Throwable) {
            //
        }

        $this->attachStoryCovers();
        $this->attachCreatorAvatars();
    }

    private function attachCreatorAvatars(): void
    {
        $flag = storage_path('app/avatars-attached.flag');

        if (file_exists($flag)) {
            return;
        }

        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\AvatarRepairSeeder',
                '--force' => true,
            ]);

            file_put_contents($flag, now()->toDateTimeString());
        } catch (Throwable) {
            //
        }
    }

    private function attachStoryCovers(): void
    {
        $flag = storage_path('app/covers-attached-v2.flag');

        if (file_exists($flag)) {
            return;
        }

        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ImageRepairSeeder',
                '--force' => true,
            ]);

            file_put_contents($flag, now()->toDateTimeString());
        } catch (Throwable) {
            //
        }
    }
}
