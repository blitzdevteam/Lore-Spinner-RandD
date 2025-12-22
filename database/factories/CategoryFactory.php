<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
final class CategoryFactory extends Factory
{
    private array $genres = [
        'High Fantasy',
        'Dark Fantasy',
        'Urban Fantasy',
        'Sword & Sorcery',
        'Epic Adventure',
        'Gothic Horror',
        'Steampunk',
        'Mythological',
        'Post-Apocalyptic',
        'Cosmic Horror',
        'Dungeon Crawl',
        'Political Intrigue',
        'Mystery & Investigation',
        'Survival Horror',
        'Planar Adventure',
        'Maritime Adventure',
        'Wilderness Exploration',
        'Court Drama',
        'War Campaign',
        'Heist & Espionage',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->randomElement($this->genres),
        ];
    }
}
