<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_users()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('users.index'));

        $response->assertInertia(
            fn($page) =>
            $page
                ->component('Users/Index')
                ->has('users')
        );
    }

    public function test_it_search_users()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $user = User::factory()->create();

        $this->actingAs($admin);

        $response = $this->get(route('users.index', [
            'q' => $user->email,
        ]));

        $response->assertInertia(
            fn($page) => $page
                ->component('Users/Index')
                ->has('users.data', 1)
                ->where('users.data.0.email', $user->email)
        );
    }

    public function test_admin_can_access_user_create_screen()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('users.create'))
            ->assertStatus(200)
            ->assertInertia(
                fn($page) =>
                $page->component('Users/Create')
            );
    }

    public function test_user_cannot_access_user_create_screen()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('users.create'))
            ->assertForbidden();
    }

    public function test_admin_can_create_a_user()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->randomElement(['admin', 'user']),
            'password' => 'Password123#',
            'password_confirmation' => 'Password123#',
        ];

        $this
            ->actingAs($admin)
            ->post('/users', $data)
            ->assertRedirect('/users');

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);
    }

    public function test_user_cannot_create_a_user()
    {
        $user = User::factory()->create();

        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->randomElement(['admin', 'user']),
            'password' => 'Password123#',
            'password_confirmation' => 'Password123#',
        ];

        $this
            ->actingAs($user)
            ->post('/users', $data)
            ->assertForbidden();
    }

    public function test_password_confirmation_does_not_math()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->randomElement(['admin', 'user']),
            'password' => 'Password123#',
            'password_confirmation' => 'Password123#Wrong',
        ];

        $this
            ->actingAs($admin)
            ->post('/users', $data)
            ->assertFound('/users');
    }

    public function test_admin_can_edit_a_user()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create();

        $this
            ->actingAs($admin)
            ->put("/users/{$user->id}", [
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin',
            ])

            ->assertRedirect("/users");

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_delete_a_user()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create();

        $this
            ->actingAs($admin)
            ->delete("/users/{$user->id}")
            ->assertRedirect("/users");

        $this->assertDatabaseMissing('users', [
            'email' => $user->email
        ]);
    }
}
