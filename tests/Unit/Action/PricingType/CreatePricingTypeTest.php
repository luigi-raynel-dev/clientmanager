<?php

use App\Actions\PricingTypes\CreatePricingType;
use App\DTO\PricingType\PricingTypeData;
use App\Infrastructure\Persistence\Eloquent\EloquentPricingTypeRepository;
use App\Models\PricingType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a pricing type', function () {

    $data = new PricingTypeData(
        name: fake()->word()
    );
    $action = new CreatePricingType(new EloquentPricingTypeRepository());

    $pricing_type = $action->execute($data);

    expect($pricing_type)->toBeInstanceOf(PricingType::class);

    $this->assertDatabaseHas('pricing_types', [
        'id' => $pricing_type->id,
    ]);
});
