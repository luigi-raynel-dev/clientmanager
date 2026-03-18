<?php

use App\Actions\Services\DeleteService;
use App\Infrastructure\Persistence\Eloquent\EloquentServiceRepository;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('deletes a service', function () {
    $service = Service::factory()->create();

    $action = new DeleteService(new EloquentServiceRepository());

    $action->execute($service->id);

    $this->assertSoftDeleted('services', [
        'id' => $service->id,
    ]);
});
