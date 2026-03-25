<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'base_price' => fake()->randomFloat(2, 10, 100),
            'pricing_type_id' => fake()->boolean() ? null : fake()->randomElement([1, 2, 3]),
            'estimated_duration_minutes' => fake()->numberBetween(1, 12),
            'estimated_duration_type' => fake()->randomElement(['minutes', 'hours', 'days', 'weeks', 'months']),
            'is_active' => fake()->boolean(70), // 70% chance of being active
        ];
    }
}
