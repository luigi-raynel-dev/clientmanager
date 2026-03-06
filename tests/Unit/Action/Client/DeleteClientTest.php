<?php

use App\Actions\Clients\DeleteClient;
use App\Infrastructure\Persistence\Eloquent\EloquentClientRepository;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('deletes a client', function () {
    $client = Client::factory()->create();

    $action = new DeleteClient(new EloquentClientRepository());

    $action->execute($client->id);

    $this->assertDatabaseMissing('clients', [
        'id' => $client->id,
    ]);
});
