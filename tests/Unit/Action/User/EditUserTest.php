<?php

use App\Actions\Users\EditUser;
use App\DTO\User\UserData;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('edit a user', function () {
    $user = User::factory()->create();

    $data = new UserData(
        name: $user->name,
        email: $user->email,
        role: 'admin'
    );
    $action = new EditUser(new EloquentUserRepository());

    $user = $action->execute($user->id, $data);

    expect($user)->toBeInstanceOf(User::class);

    $this->assertDatabaseHas('users', [
        'email' => $data->email,
        'role' => $data->role,
    ]);
});
