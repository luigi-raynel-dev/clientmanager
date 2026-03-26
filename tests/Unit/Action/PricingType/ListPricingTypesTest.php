<?php

use App\Actions\PricingTypes\ListPricingTypes;
use App\Infrastructure\Persistence\Eloquent\EloquentPricingTypeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('lists pricing types', function () {
    $action = new ListPricingTypes(new EloquentPricingTypeRepository());
    $result = $action->execute();

    expect($result)->toBeInstanceOf(Collection::class);
});
