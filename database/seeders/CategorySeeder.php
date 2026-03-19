<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

final class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getCategories() as $title) {
            Category::firstOrCreate(['title' => $title]);
        }
    }

    /**
     * @return list<string>
     */
    private function getCategories(): array
    {
        return [
            'Science Fiction',
            'Action Thriller',
            'Dark Fantasy',
            'Horror',
            'Thriller',
            'Historical Adventure',
            'Fantasy Adventure',
            'Adventure',
            'Techno-Thriller',
            'Supernatural Thriller',
            'Military Drama',
            'Dystopian',
            'Mystery',
            'Drama',
            'Cyberpunk',
        ];
    }
}
