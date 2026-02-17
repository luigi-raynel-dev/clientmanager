<?php

namespace App\Actions\Users;

use App\Domain\Users\Repositories\UserRepository;

class GetUser
{
  public function __construct(private UserRepository $repository) {}

  public function execute(int $id)
  {
    return $this->repository->get($id);
  }
}
