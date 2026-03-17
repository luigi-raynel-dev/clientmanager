<?php

namespace App\Actions\Services;

use App\Domain\Services\Repositories\ServiceRepository;

class GetService
{
  public function __construct(private ServiceRepository $repository) {}

  public function execute(int $id)
  {
    return $this->repository->get($id);
  }
}
