<?php

use App\Actions\Users\ChangeUserStatusAction;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('blocks a user', function () {
    $user = User::factory()->create();

    $action = new ChangeUserStatusAction(new EloquentUserRepository());

    $action->execute($user->id, true);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'is_blocked' => true,
    ]);
});

it('unblocks a user', function () {
    $user = User::factory()->create([
        'is_blocked' => true,
    ]);

    $action = new ChangeUserStatusAction(new EloquentUserRepository());

    $action->execute($user->id, false);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'is_blocked' => false,
    ]);
});
