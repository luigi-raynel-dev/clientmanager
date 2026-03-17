<?php

namespace App\Actions\Services;

use App\Domain\Services\Repositories\ServiceRepository;
use App\DTO\Service\ServiceData;
use App\Models\Service;

class EditService
{
    public function __construct(private ServiceRepository $repository) {}

    public function execute(int $id, ServiceData $data): Service
    {
        return $this->repository->edit($id, $data);
    }
}
