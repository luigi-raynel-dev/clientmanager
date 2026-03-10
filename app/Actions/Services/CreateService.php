<?php

namespace App\Actions\Services;

use App\Domain\Services\Repositories\ServiceRepository;
use App\DTO\Service\ServiceData;
use App\Models\Service;

class CreateService
{
    public function __construct(private ServiceRepository $repository) {}

    public function execute(ServiceData $data): Service
    {
        return $this->repository->create($data);
    }
}
