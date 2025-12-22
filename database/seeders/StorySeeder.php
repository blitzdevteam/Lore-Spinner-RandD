<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Story;
use App\Models\Writer;
use Illuminate\Database\Seeder;

final class StorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Story::factory()
            ->recycle(Category::all())
            ->recycle(Writer::all())
            ->count(25)
            ->create();
    }
}
