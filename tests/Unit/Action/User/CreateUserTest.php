<?php

use App\Actions\Users\CreateUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a user with hashed password and role', function () {
    $action = new CreateUser();

    $data = [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'role' => fake()->randomElement(['admin', 'user']),
        'password' => 'Password123#',
    ];

    $user = $action->execute($data);

    expect($user)->toBeInstanceOf(User::class);

    $this->assertDatabaseHas('users', [
        'email' => $data['email'],
        'role' => $data['role'],
    ]);

    expect(
        Hash::check($data['password'], $user->password)
    )->toBeTrue();
});
