<?php

namespace App\Actions\PricingTypes;

use App\Domain\PricingTypes\Repositories\PricingTypeRepository;
use App\DTO\PricingType\PricingTypeData;
use App\Models\PricingType;

class CreatePricingType
{
    public function __construct(private PricingTypeRepository $repository) {}

    public function execute(PricingTypeData $data): PricingType
    {
        return $this->repository->create($data);
    }
}
