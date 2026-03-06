<?php

namespace App\Actions\Clients;

use App\Domain\Clients\Repositories\ClientRepository;

class GetClient
{
  public function __construct(private ClientRepository $repository) {}

  public function execute(int $id)
  {
    return $this->repository->get($id);
  }
}
