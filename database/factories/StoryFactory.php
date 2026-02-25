<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Story\StoryRatingEnum;
use App\Enums\Story\StoryStatusEnum;
use App\Models\Category;
use App\Models\Creator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $title = fake()->sentence();

        return [
            'category_id' => Category::factory(),
            'creator_id' => Creator::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'teaser' => fake()->paragraph(),
            'opening' => fake()->paragraph(),
            'status' => fake()->randomElement(StoryStatusEnum::values()),
            'rating' => fake()->randomElement(StoryRatingEnum::values()),
            'published_at' => fake()->optional()->dateTimeThisYear(),
        ];
    }
}
