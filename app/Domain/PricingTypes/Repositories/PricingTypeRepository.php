<?php

namespace App\Domain\PricingTypes\Repositories;

use App\DTO\PricingType\PricingTypeData;
use App\Models\PricingType;
use Illuminate\Database\Eloquent\Collection;

interface PricingTypeRepository
{
  public function list(): Collection;

  public function create(PricingTypeData $data): PricingType;
}
