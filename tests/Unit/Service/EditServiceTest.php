<?php

use App\Actions\Services\EditService;
use App\DTO\Service\ServiceData;
use App\Infrastructure\Persistence\Eloquent\EloquentServiceRepository;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('edits a service', function () {
    $service = Service::factory()->create();

    $data = new ServiceData(
        name: fake()->name(),
        description: $service->description ?? null,
        base_price: $service->base_price ?? null,
        pricing_type_id: $service->pricing_type_id ?? null,
        estimated_duration_minutes: $service->estimated_duration_minutes ?? null,
        estimated_duration_type: $service->estimated_duration_type ?? null,
        is_active: $service->is_active
    );
    $action = new EditService(new EloquentServiceRepository());

    $service = $action->execute($service->id, $data);

    expect($service)->toBeInstanceOf(Service::class);

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'name' => $data->name,
    ]);
});
