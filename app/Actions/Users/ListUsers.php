<?php

namespace App\Actions\Users;

use App\Domain\Users\Repositories\UserRepository;
use App\DTO\User\UserFilter;

class ListUsers
{
  public function __construct(private UserRepository $repository) {}

  public function execute(?UserFilter $filter = null)
  {
    return $filter ? $this->repository->search($filter) : $this->repository->list();
  }
}
