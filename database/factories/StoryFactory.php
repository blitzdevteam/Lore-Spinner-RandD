<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Story\StoryRatingEnum;
use App\Enums\Story\StoryStatusEnum;
use App\Models\Category;
use App\Models\Creator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Story>
 */
final class StoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'creator_id' => Creator::factory(),
            'title' => fake()->sentence(),
            'teaser' => fake()->paragraph(),
            'status' => fake()->randomElement(StoryStatusEnum::values()),
            'rating' => fake()->randomElement(StoryRatingEnum::values()),
            'published_at' => fake()->optional()->dateTimeThisYear(),
        ];
    }
}
