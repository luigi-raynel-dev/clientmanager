<?php

use App\Actions\Users\CreateUser;
use App\DTO\User\UserData;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a user with hashed password and role', function () {

    $data = new UserData(
        name: fake()->name(),
        email: fake()->unique()->safeEmail(),
        role: fake()->randomElement(['admin', 'user']),
        password: 'Password123#',
    );
    $action = new CreateUser(new EloquentUserRepository());

    $user = $action->execute($data);

    expect($user)->toBeInstanceOf(User::class);

    $this->assertDatabaseHas('users', [
        'email' => $data->email,
        'role' => $data->role,
    ]);

    expect(
        Hash::check($data->password, $user->password)
    )->toBeTrue();
});
