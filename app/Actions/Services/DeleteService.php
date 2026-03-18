<?php

namespace App\Actions\Services;

use App\Domain\Services\Repositories\ServiceRepository;

class DeleteService
{
    public function __construct(private ServiceRepository $repository) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
