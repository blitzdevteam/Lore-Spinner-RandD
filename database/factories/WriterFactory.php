<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Writer>
 */
final class WriterFactory extends Factory
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
            'username' => fake()->userName(),
            'bio' => fake()->paragraph(),
            'is_active' => fake()->boolean(),
            'email_verified_at' => fake()->dateTime(),
            'last_active_at' => fake()->dateTime(),
        ];
    }
}
