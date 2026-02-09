<?php

namespace App\Actions\Users;

use App\Domain\Users\Repositories\UserRepository;
use App\DTO\User\UserFilter;
use App\Models\User;

class ListUsers
{
  public function __construct(private UserRepository $repository) {}

  public function execute(UserFilter $filter)
  {
    return $this->repository->search($filter);
  }
}
