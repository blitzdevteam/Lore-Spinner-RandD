<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Writer;
use Illuminate\Database\Seeder;

final class WriterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Writer::factory()->count(10)->create();

        Writer::factory()->create([
            'first_name' => 'nima',
            'last_name' => 'asaadi',
            'email' => 'ni4.asadi.b@gmail.com',
            'password' => 'n.ima1234',
        ]);
    }
}
