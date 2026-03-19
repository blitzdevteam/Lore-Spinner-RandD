<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

final class CreatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getCreators() as $data) {
            $creator = null;

            Creator::withoutEvents(function () use ($data, &$creator): void {
                $creator = Creator::firstOrCreate(
                    ['email' => $data['email']],
                    [
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'username' => $data['username'],
                        'password' => 'password',
                        'bio' => $data['bio'],
                    ]
                );
            });

            DB::table('creators')
                ->where('id', $creator->id)
                ->update([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'bio' => $data['bio'],
                ]);

            $avatarPath = database_path('stories/avatars/'.$data['avatar']);

            if (File::exists($avatarPath) && ! $creator->getFirstMedia('avatar')) {
                $creator->addMedia($avatarPath)
                    ->preservingOriginal()
                    ->usingFileName('avatar-'.$creator->id.'.'.pathinfo($avatarPath, PATHINFO_EXTENSION))
                    ->toMediaCollection('avatar', 'public');
            }

            $this->command->info("Creator ensured: {$data['first_name']} {$data['last_name']}");
        }
    }

    /**
     * @return list<array<string, string>>
     */
    private function getCreators(): array
    {
        return [
            [
                'first_name' => 'Thomas',
                'last_name' => 'Wittmer',
                'username' => 'thomaswittmer',
                'email' => 'thomas@lorespinner.com',
                'bio' => 'Mythic blockbuster storyteller building high-concept cinematic worlds fueled by awe, danger, and emotionally charged spectacle.',
                'avatar' => 'THOMAS WITTMER - PROFILE PIC.jpg',
            ],
            [
                'first_name' => 'Hilton',
                'last_name' => 'Williams',
                'username' => 'hiltonwilliams',
                'email' => 'hilton@lorespinner.com',
                'bio' => 'Writer of elevated, atmospheric stories where mystery, identity, and human fracture unfold with haunting emotional depth.',
                'avatar' => 'Hilton Williams - PROFILE PIC.jpg',
            ],
            [
                'first_name' => 'Rand',
                'last_name' => 'Soares',
                'username' => 'randsoares',
                'email' => 'rand@lorespinner.com',
                'bio' => 'Hollywood veteran and cinematic world-builder crafting bold, high-concept adventures defined by scale, heart, danger, and timeless blockbuster storytelling.',
                'avatar' => 'RAND SOARES - PROFILE PIC.jpg',
            ],
            [
                'first_name' => 'FREEP!',
                'last_name' => '',
                'username' => 'freep',
                'email' => 'freep@lorespinner.com',
                'bio' => 'Hollywood writing team creating high-impact television worlds where serialized mythology, procedural engines, and commercial concept collide.',
                'avatar' => 'FREEP1 - PROFILE PIC.jpg',
            ],
            [
                'first_name' => 'The Classics, Unbound',
                'last_name' => '',
                'username' => 'theclassicsunbound',
                'email' => 'classics@lorespinner.com',
                'bio' => "Enter the world's most iconic classic stories—now immersive, interactive adventures where your choices reshape timeless legends.",
                'avatar' => 'THE CLASSICS, UNBOUND - PROFILE PIC.jpg',
            ],
        ];
    }
}
