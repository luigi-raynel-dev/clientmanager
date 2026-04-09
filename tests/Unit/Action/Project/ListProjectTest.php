<?php

use App\Actions\Projects\ListProjects;
use App\Infrastructure\Persistence\Eloquent\EloquentProjectRepository;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('lists services', function () {
    Project::factory(20)->create();

    $filter = new \App\DTO\Project\ProjectFilter(
        search: null,
        priority: null,
        status_id: null,
        order_by: null,
        order_direction: null,
        per_page: 15,
        page: 1
    );

    $action = new ListProjects(new EloquentProjectRepository());
    $result = $action->execute($filter);

    expect($result)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
    expect($result->total())->toBe(20);
    expect($result->perPage())->toBe(15);
});
