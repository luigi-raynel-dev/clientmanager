<?php

namespace App\Actions\Services;

use App\Domain\Services\Repositories\ServiceRepository;
use App\DTO\Service\ServiceFilter;

class ListServices
{
  public function __construct(private ServiceRepository $repository) {}

  public function execute(?ServiceFilter $filter = null)
  {
    return $filter ? $this->repository->search($filter) : $this->repository->list();
  }
}
