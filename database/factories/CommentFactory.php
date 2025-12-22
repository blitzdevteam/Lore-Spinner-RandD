<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Comment\CommentStatusEnum;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Override;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
final class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph(),
            'status' => fake()->randomElement(CommentStatusEnum::values()),
        ];
    }

    /**
     * Configure the model factory.
     */
    #[Override]
    public function configure(): self|Factory
    {
        return $this->afterMaking(function (Comment $comment): void {
            if ($comment->status === CommentStatusEnum::APPROVED) {
                $dateThisMonth = fake()->dateTimeThisMonth();
                $comment->approved_at = $dateThisMonth;
                $comment->updated_at = $dateThisMonth;
                $comment->created_at = Carbon::make($dateThisMonth)->subDays(7);
            }
        });
    }
}
