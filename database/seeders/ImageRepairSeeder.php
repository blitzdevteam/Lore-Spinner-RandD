<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

final class ImageRepairSeeder extends Seeder
{
    public function run(): void
    {
        $previousQueue = config('queue.default');
        config(['queue.default' => 'sync']);

        try {
            Artisan::call('images:generate-missing');
            $this->command->info('Image generation complete.');
        } finally {
            config(['queue.default' => $previousQueue]);
        }
    }
}
