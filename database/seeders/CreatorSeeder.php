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
            'first_name' => 'nima',
            'last_name' => 'asaadi',
            'email' => 'ni4.asadi.b@gmail.com',
            'password' => 'n.ima1234',
        ]);
    }
}
