<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

final class CreatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Creator::factory()->count(10)->create();

        // Writer / Creator account for the stories
        $thomas = Creator::factory()->create([
            'first_name' => 'Thomas',
            'last_name' => 'Wittmer',
            'username' => 'thomaswittmer',
            'email' => 'thomas@lorespinner.com',
            'password' => 'password',
            'bio' => 'Thomas Wittmer is a narrative designer and storyteller focused on interactive fiction and immersive worldbuilding. At Lorespinner, he shapes story architecture that blends cinematic writing with player agency, crafting worlds meant to be lived, not just read.',
        ]);

        $avatarPath = base_path('database/stories/image.png');
        if (File::exists($avatarPath)) {
            $thomas->addMedia($avatarPath)
                ->preservingOriginal()
                ->usingFileName("avatar-{$thomas->id}.png")
                ->toMediaCollection('avatar', 'public');
        }
    }
}
