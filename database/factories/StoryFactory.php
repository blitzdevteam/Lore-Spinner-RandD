<?php

namespace Database\Factories;

use App\Enums\Story\RatingEnum;
use App\Enums\Story\StatusEnum;
use App\Models\Category;
use App\Models\Writer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Story>
 */
class StoryFactory extends Factory
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
            'writer_id' => Writer::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(StatusEnum::values()),
            'rating' => fake()->randomElement(RatingEnum::values()),
            'published_at' => fake()->optional()->dateTimeThisYear(),
        ];
    }
}
