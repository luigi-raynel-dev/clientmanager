<?php

use App\Actions\Clients\EditClient;
use App\DTO\Client\ClientData;
use App\Infrastructure\Persistence\Eloquent\EloquentClientRepository;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('edits a client', function () {
    $client = Client::factory()->create();

    $data = new ClientData(
        name: fake()->name(),
        email: fake()->unique()->safeEmail(),
    );
    $action = new EditClient(new EloquentClientRepository());

    $client = $action->execute($client->id, $data);

    expect($client)->toBeInstanceOf(Client::class);

    $this->assertDatabaseHas('clients', [
        'id' => $client->id,
        'name' => $data->name,
        'email' => $data->email,
    ]);
});
