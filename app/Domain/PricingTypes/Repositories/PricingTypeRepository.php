<?php

namespace App\Domain\PricingTypes\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface PricingTypeRepository
{
  public function list(): Collection;
}
