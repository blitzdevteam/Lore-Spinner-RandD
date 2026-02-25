<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Chapter\ChapterStatusEnum;
use App\Models\Story;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
final class ChapterFactory extends Factory
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
            'story_id' => Story::factory(),
            'position' => ++$position,
            'title' => fake()->sentence(3),
            'teaser' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'status' => ChapterStatusEnum::READY_TO_PLAY->value,
        ];
    }

    /**
     * Set the chapter status to ready to play.
     */
    public function readyToPlay(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ChapterStatusEnum::READY_TO_PLAY->value,
        ]);
    }
}
