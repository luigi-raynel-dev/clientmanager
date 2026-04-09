<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Configure the factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Project $project) {
            $services = Service::factory()->count(2)->create();
            $project->services()->attach($services);
        });
    }

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
            'priority' => fake()->randomElement(['Low', 'Medium', 'High']),
            'status_id' => fake()->randomElement([1, 2, 3, 4, 5])
        ];
    }
}
