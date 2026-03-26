<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Models\PricingType;
use App\Domain\PricingTypes\Repositories\PricingTypeRepository;
use Illuminate\Database\Eloquent\Collection;

class EloquentPricingTypeRepository implements PricingTypeRepository
{
  public function list(): Collection
  {
    return PricingType::all();
  }
}
