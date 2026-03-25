<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_services()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('services.index'));

        $response->assertInertia(
            fn($page) =>
            $page
                ->component('Services/Index')
                ->has('services')
        );
    }

    public function test_admin_can_access_service_create_screen()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('services.create'))
            ->assertStatus(200)
            ->assertInertia(
                fn($page) =>
                $page->component('Services/Create')
            );
    }

    public function test_admin_can_create_a_service()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $data = [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'base_price' => fake()->randomFloat(2, 10, 100),
            'pricing_type_id' => fake()->boolean() ? null : fake()->randomElement([1, 2, 3]),
            'estimated_duration_minutes' => fake()->numberBetween(1, 12),
            'estimated_duration_type' => fake()->randomElement(['minutes', 'hours', 'days', 'weeks', 'months']),
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
            'pricing_type_id' => fake()->boolean() ? null : fake()->randomElement([1, 2, 3]),
            'estimated_duration_minutes' => fake()->numberBetween(1, 12),
            'is_active' => fake()->boolean(70),
        ];

        $this
            ->actingAs($user)
            ->post('/services', $data)
            ->assertForbidden();
    }

    public function test_admin_can_edit_a_service()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $service = Service::factory()->create();

        $data = [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'base_price' => fake()->randomFloat(2, 10, 100),
            'pricing_type_id' => fake()->boolean() ? null : fake()->randomElement([1, 2, 3]),
            'estimated_duration_minutes' => fake()->numberBetween(1, 12),
            'estimated_duration_type' => fake()->randomElement(['minutes', 'hours', 'days', 'weeks', 'months']),
            'is_active' => fake()->boolean(70),
        ];

        $this
            ->actingAs($admin)
            ->put("/services/{$service->id}", $data)
            ->assertRedirect("/services");

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => $data['name'],
        ]);
    }

    public function test_admin_can_delete_a_service()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $service = Service::factory()->create();

        $this
            ->actingAs($admin)
            ->delete("/services/{$service->id}")
            ->assertRedirect("/services");

        $this->assertSoftDeleted('services', [
            'id' => $service->id
        ]);
    }

    public function test_admin_can_active_a_service()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $service = Service::factory()->create([
            'is_active' => false,
        ]);

        $this
            ->actingAs($admin)
            ->patch("/services/{$service->id}/status", [
                'is_active' => true,
            ])
            ->assertRedirect("/services");

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_deactive_a_service()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $service = Service::factory()->create([
            'is_active' => true,
        ]);

        $this
            ->actingAs($admin)
            ->patch("/services/{$service->id}/status", [
                'is_active' => false,
            ])
            ->assertRedirect("/services");

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'is_active' => false,
        ]);
    }
}
