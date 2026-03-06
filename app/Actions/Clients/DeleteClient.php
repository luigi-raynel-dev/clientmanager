<?php

namespace App\Actions\Clients;

use App\Domain\Clients\Repositories\ClientRepository;

class DeleteClient
{
    public function __construct(private ClientRepository $repository) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
