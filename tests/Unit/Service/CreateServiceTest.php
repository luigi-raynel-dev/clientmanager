<?php

use App\Actions\Services\CreateService;
use App\DTO\Service\ServiceData;
use App\Infrastructure\Persistence\Eloquent\EloquentServiceRepository;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a service', function () {

    $data = new ServiceData(
        name: fake()->words(3, true),
        description: fake()->sentence(),
        base_price: fake()->randomFloat(2, 10, 100),
        price_type: fake()->randomElement(['fixed', 'unit', 'hourly', 'daily']),
        estimated_duration_minutes: fake()->numberBetween(1, 12),
        estimated_duration_type: fake()->randomElement(['minutes', 'hours', 'days', 'weeks', 'months']),
    );
    $action = new CreateService(new EloquentServiceRepository());

    $service = $action->execute($data);

    expect($service)->toBeInstanceOf(Service::class);

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
    ]);
});
