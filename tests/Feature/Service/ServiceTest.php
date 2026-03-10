<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_service()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $data = [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'base_price' => fake()->randomFloat(2, 10, 100),
            'price_type' => fake()->randomElement(['fixed', 'unit', 'hourly', 'daily']),
            'estimated_duration_hours' => fake()->numberBetween(1, 12),
            'is_active' => fake()->boolean(70),
        ];

        $this
            ->actingAs($admin)
            ->post('/services', $data)
            ->assertRedirect('/services');

        $this->assertDatabaseHas('services', [
            'name' => $data['name'],
        ]);
    }

    public function test_user_cannot_create_a_service()
    {
        $user = User::factory()->create();

        $data = [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'base_price' => fake()->randomFloat(2, 10, 100),
            'price_type' => fake()->randomElement(['fixed', 'unit', 'hourly', 'daily']),
            'estimated_duration_hours' => fake()->numberBetween(1, 12),
            'is_active' => fake()->boolean(70),
        ];

        $this
            ->actingAs($user)
            ->post('/services', $data)
            ->assertForbidden();
    }
}
