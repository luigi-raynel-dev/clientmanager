<?php

namespace App\Actions\Clients;

use App\Domain\Clients\Repositories\ClientRepository;
use App\DTO\Client\ClientData;
use App\Models\Client;

class EditClient
{
    public function __construct(private ClientRepository $repository) {}

    public function execute(int $id, ClientData $data): Client
    {
        return $this->repository->edit($id, $data);
    }
}
