<?php

namespace App\Actions\Clients;

use App\Domain\Clients\Repositories\ClientRepository;
use App\DTO\Client\ClientData;
use App\Models\Client;

class CreateClient
{
    public function __construct(private ClientRepository $repository) {}

    public function execute(ClientData $data): Client
    {
        return $this->repository->create($data);
    }
}
