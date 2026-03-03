<?php

namespace Tests\Feature;

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
}
