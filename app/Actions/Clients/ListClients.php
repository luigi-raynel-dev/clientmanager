<?php

namespace App\Actions\Clients;

use App\Domain\Clients\Repositories\ClientRepository;
use App\DTO\Client\ClientFilter;

class ListClients
{
  public function __construct(private ClientRepository $repository) {}

  public function execute(ClientFilter $filter)
  {
    return $this->repository->search($filter);
  }
}
