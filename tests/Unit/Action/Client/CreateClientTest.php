<?php

use App\Actions\Clients\CreateClient;
use App\DTO\Client\ClientData;
use App\Infrastructure\Persistence\Eloquent\EloquentClientRepository;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a client', function () {

    $data = new ClientData(
        name: fake()->name(),
        email: fake()->unique()->safeEmail(),
    );
    $action = new CreateClient(new EloquentClientRepository());

    $client = $action->execute($data);

    expect($client)->toBeInstanceOf(Client::class);

    $this->assertDatabaseHas('clients', [
        'email' => $data->email,
    ]);
});
