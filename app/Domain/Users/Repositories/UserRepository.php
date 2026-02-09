<?php

namespace App\Domain\Users\Repositories;

use App\DTO\User\UserFilter;
use Illuminate\Support\Collection;

interface UserRepository
{
  public function search(UserFilter $filter): Collection;
}
