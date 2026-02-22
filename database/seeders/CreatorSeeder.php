<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Creator;
use Illuminate\Database\Seeder;

final class CreatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Creator::factory()->count(10)->create();

        Creator::factory()->create([
            'first_name' => 'john',
            'last_name' => 'doe',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
    }
}
