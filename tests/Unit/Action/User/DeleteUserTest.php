<?php

use App\Actions\Users\DeleteUser;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('deletes a user', function () {
    $user = User::factory()->create();

    $action = new DeleteUser(new EloquentUserRepository());

    $action->execute($user->id);

    $this->assertDatabaseMissing('users', [
        'email' => $user->email,
    ]);
});
