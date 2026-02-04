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
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('users.index'));

        $response->assertInertia(
            fn($page) =>
            $page
                ->component('Users/Index')
                ->has('users')
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
}
