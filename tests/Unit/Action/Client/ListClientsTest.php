<?php

use App\Actions\Clients\ListClients;
use App\Infrastructure\Persistence\Eloquent\EloquentClientRepository;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('lists clients', function () {
    Client::factory(20)->create();

    $filter = new \App\DTO\Client\ClientFilter(
        search: null,
        order_by: 'created_at',
        order_direction: 'desc',
        per_page: 15,
        page: 1,
    );

    $action = new ListClients(new EloquentClientRepository());
    $result = $action->execute($filter);

    expect($result)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
    expect($result->total())->toBe(20);
    expect($result->perPage())->toBe(15);
});
