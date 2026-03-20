<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

final class AvatarRepairSeeder extends Seeder
{
    public function run(): void
    {
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
                    $this->command->warn("Creator not found: {$email}");

                    continue;
                }

                $creator->clearMediaCollection('avatar');

                $source = database_path('stories/avatars/'.$filename);

                if (! File::exists($source)) {
                    $this->command->warn("Source missing: {$filename}");

                    continue;
                }

                $creator->addMedia($source)
                    ->preservingOriginal()
                    ->usingFileName('avatar-'.$creator->id.'.'.pathinfo($source, PATHINFO_EXTENSION))
                    ->toMediaCollection('avatar', 'public');

                $this->command->info("Avatar attached: {$creator->first_name} {$creator->last_name}");
            }
        });
    }
}
