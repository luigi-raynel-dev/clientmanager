<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_project_create_screen()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('projects.create'))
            ->assertStatus(200)
            ->assertInertia(
                fn($page) =>
                $page->component('Projects/Create')
            );
    }

    public function test_admin_can_create_a_project()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Aggregate related roots (factories)
        $services = Service::factory()->count(2)->create();
        $clients = Client::factory()->count(1)->create();
        $professionals = User::factory()->count(2)->create();

        $data = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status_id' => fake()->randomElement([1, 2, 3]),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High']),
            'start_date' => now(),
            'end_date' => now()->addDays(10),
            'discount_percentage' => fake()->randomFloat(2, 0, 100),
            'services' => $services->pluck('id')->toArray(),
            'clients' => $clients->pluck('id')->toArray(),
            'professionals' => $professionals->pluck('id')->toArray(),
        ];

        $this
            ->actingAs($admin)
            ->post('/projects', $data)
            ->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', [
            'name' => $data['name'],
            'description' => $data['description'],
            'status_id' => $data['status_id'],
            'priority' => $data['priority'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'discount_percentage' => $data['discount_percentage'],
        ]);
    }

    public function test_user_cannot_create_a_project()
    {
        $user = User::factory()->create();

        // Aggregate related roots (factories)
        $services = Service::factory()->count(2)->create();
        $clients = Client::factory()->count(1)->create();
        $professionals = User::factory()->count(2)->create();

        $data = [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status_id' => fake()->randomElement([1, 2, 3]),
            'priority' => fake()->randomElement(['Low', 'Medium', 'High']),
            'start_date' => now(),
            'end_date' => now()->addDays(10),
            'discount_percentage' => fake()->randomFloat(2, 0, 100),
            'services' => $services->pluck('id')->toArray(),
            'clients' => $clients->pluck('id')->toArray(),
            'professionals' => $professionals->pluck('id')->toArray(),
        ];

        $this
            ->actingAs($user)
            ->post('/projects', $data)
            ->assertForbidden();
    }
}
