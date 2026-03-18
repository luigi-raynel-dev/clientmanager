<?php

use App\Actions\Services\ChangeServiceStatusAction;
use App\Infrastructure\Persistence\Eloquent\EloquentServiceRepository;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('active a service', function () {
    $service = Service::factory()->create([
        'is_active' => false,
    ]);

    $action = new ChangeServiceStatusAction(new EloquentServiceRepository());

    $action->execute($service->id, true);

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'is_active' => true,
    ]);
});

it('deactive a service', function () {
    $service = Service::factory()->create([
        'is_active' => true,
    ]);

    $action = new ChangeServiceStatusAction(new EloquentServiceRepository());

    $action->execute($service->id, false);

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'is_active' => false,
    ]);
});
