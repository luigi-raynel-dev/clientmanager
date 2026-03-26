<?php

namespace App\Actions\PricingTypes;

use App\Domain\PricingTypes\Repositories\PricingTypeRepository;

class ListPricingTypes
{
  public function __construct(private PricingTypeRepository $repository) {}

  public function execute()
  {
    return $this->repository->list();
  }
}
