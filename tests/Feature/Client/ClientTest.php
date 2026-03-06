<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_clients()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('clients.index'));

        $response->assertInertia(
            fn($page) =>
            $page
                ->component('Clients/Index')
                ->has('clients')
        );
    }

    public function test_admin_can_access_client_create_screen()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('clients.create'))
            ->assertStatus(200)
            ->assertInertia(
                fn($page) =>
                $page->component('Clients/Create')
            );
    }

    public function test_client_cannot_access_client_create_screen()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('clients.create'))
            ->assertForbidden();
    }

    public function test_admin_can_create_a_client()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
        ];

        $this
            ->actingAs($admin)
            ->post('/clients', $data)
            ->assertRedirect('/clients');

        $this->assertDatabaseHas('clients', [
            'email' => $data['email'],
        ]);
    }

    public function test_client_cannot_create_a_client()
    {
        $user = User::factory()->create();

        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
        ];

        $this
            ->actingAs($user)
            ->post('/clients', $data)
            ->assertForbidden();
    }

    public function test_admin_can_edit_a_client()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $client = Client::factory()->create();

        $this
            ->actingAs($admin)
            ->put("/clients/{$client->id}", [
                'name' => $client->name,
                'email' => $client->email,
            ])
            ->assertRedirect("/clients");

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'email' => $client->email,
            'name' => $client->name,
        ]);
    }

    public function test_admin_can_delete_a_client()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $client = Client::factory()->create();

        $this
            ->actingAs($admin)
            ->delete("/clients/{$client->id}")
            ->assertRedirect("/clients");

        $this->assertDatabaseMissing('clients', [
            'id' => $client->id
        ]);
    }
}
