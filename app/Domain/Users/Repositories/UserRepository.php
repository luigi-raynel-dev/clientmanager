<?php

namespace App\Domain\Users\Repositories;

use App\DTO\User\UserFilter;
// use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepository
{
  public function search(UserFilter $filter): LengthAwarePaginator;
}
