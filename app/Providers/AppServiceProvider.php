<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Creator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
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

        $this->updateManagerProfile();
        $this->repairCreatorAvatars();
        $this->repairMissingImages();
    }

    private function updateManagerProfile(): void
    {
        $flag = storage_path('app/manager-profile-updated.flag');

        if (file_exists($flag)) {
            return;
        }

        try {
            \Illuminate\Support\Facades\DB::table('managers')
                ->where('email', env('MANAGER_EMAIL', 'admin@lorespinner.com'))
                ->update([
                    'first_name' => 'Daniel N',
                    'last_name' => 'Rahimi',
                ]);

            file_put_contents($flag, now()->toDateTimeString());
        } catch (Throwable) {
            //
        }
    }

    private function repairMissingImages(): void
    {
        $flag = storage_path('app/images-repaired.flag');

        if (file_exists($flag)) {
            return;
        }

        try {
            file_put_contents($flag, now()->toDateTimeString());

            $artisan = base_path('artisan');
            $logFile = storage_path('logs/image-repair.log');

            exec("php {$artisan} db:seed --class=ImageRepairSeeder --force >> {$logFile} 2>&1 &");
        } catch (Throwable) {
            //
        }
    }

    private function repairCreatorAvatars(): void
    {
        $flag = storage_path('app/avatars-repaired.flag');

        if (file_exists($flag)) {
            return;
        }

        try {
            $avatarMap = [
                'thomas@lorespinner.com' => 'THOMAS WITTMER - PROFILE PIC.jpg',
                'hilton@lorespinner.com' => 'Hilton Williams - PROFILE PIC.jpg',
                'rand@lorespinner.com' => 'RAND SOARES - PROFILE PIC.jpg',
                'freep@lorespinner.com' => 'FREEP1 - PROFILE PIC.jpg',
                'classics@lorespinner.com' => 'THE CLASSICS, UNBOUND - PROFILE PIC.jpg',
            ];

            Creator::withoutEvents(function () use ($avatarMap): void {
                foreach ($avatarMap as $email => $filename) {
                    $creator = Creator::where('email', $email)->first();

                    if (! $creator) {
                        continue;
                    }

                    $media = $creator->getFirstMedia('avatar');

                    if ($media && file_exists($media->getPath())) {
                        continue;
                    }

                    $creator->clearMediaCollection('avatar');

                    $source = database_path('stories/avatars/'.$filename);

                    if (File::exists($source)) {
                        $creator->addMedia($source)
                            ->preservingOriginal()
                            ->usingFileName('avatar-'.$creator->id.'.'.pathinfo($source, PATHINFO_EXTENSION))
                            ->toMediaCollection('avatar', 'public');
                    }
                }
            });

            file_put_contents($flag, now()->toDateTimeString());
        } catch (Throwable) {
            //
        }
    }
}
