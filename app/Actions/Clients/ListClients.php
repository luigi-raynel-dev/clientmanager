<?php

namespace App\Actions\Clients;

use App\Domain\Clients\Repositories\ClientRepository;
use App\DTO\Client\ClientFilter;

class ListClients
{
  public function __construct(private ClientRepository $repository) {}

  public function execute(?ClientFilter $filter = null)
  {
    return $filter ? $this->repository->search($filter) : $this->repository->list();
  }
}
