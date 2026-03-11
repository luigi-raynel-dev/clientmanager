<?php

use App\Actions\Services\ListServices;
use App\Infrastructure\Persistence\Eloquent\EloquentServiceRepository;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('lists services', function () {
    Service::factory(20)->create();

    $filter = new \App\DTO\Service\ServiceFilter(
        search: null,
        order_by: 'created_at',
        order_direction: 'desc',
        per_page: 15,
        page: 1,
    );

    $action = new ListServices(new EloquentServiceRepository());
    $result = $action->execute($filter);

    expect($result)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
    expect($result->total())->toBe(20);
    expect($result->perPage())->toBe(15);
});
