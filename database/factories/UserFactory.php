<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\GenderEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Manager>
 */
final class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'nickname' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'gender' => fake()->randomElement(GenderEnum::values()),
            'bio' => fake()->paragraph(),
            'is_active' => fake()->boolean(),
            'last_active_at' => fake()->dateTime(),
        ];
    }

    /**
     * Mark the model has completed profile by setting username.
     */
    public function withCompleteProfile(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'username' => fake()->userName(),
        ]);
    }

    /**
     * Mark the model's email as verified.
     */
    public function markEmailAsVerified(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => now(),
        ]);
    }
}
