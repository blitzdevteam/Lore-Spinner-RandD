<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Creator;
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
            if (Creator::where('email', 'rand@lorespinner.com')->doesntExist()) {
                Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\ExpansionSeeder']);
            }
        } catch (Throwable) {
            // Table may not exist yet during initial migrations
        }
    }
}
