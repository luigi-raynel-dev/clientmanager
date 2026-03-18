<?php

namespace App\Actions\Services;

use App\Domain\Services\Repositories\ServiceRepository;

class ChangeServiceStatusAction
{
    public function __construct(private ServiceRepository $repository) {}

    public function execute(int $id, bool $isActive)
    {
        return $this->repository->setIsActive($id, $isActive);
    }
}
