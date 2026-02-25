<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Chapter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
final class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $position = 0;

        return [
            'chapter_id' => Chapter::factory(),
            'position' => ++$position,
            'title' => fake()->sentence(4),
            'content' => fake()->paragraphs(3, true),
            'objectives' => fake()->optional(0.7)->sentence(),
            'attributes' => fake()->optional(0.5)->randomElements(
                ['strength', 'wisdom', 'charisma', 'dexterity', 'intelligence', 'courage'],
                fake()->numberBetween(1, 3)
            ),
        ];
    }
}
