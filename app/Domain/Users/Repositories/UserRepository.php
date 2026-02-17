<?php

namespace App\Domain\Users\Repositories;

use App\DTO\User\UserData;
use App\DTO\User\UserFilter;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepository
{
  public function search(UserFilter $filter): LengthAwarePaginator;

  public function get(int $id): User;

  public function create(UserData $data): User;

  public function edit(int $id, UserData $data): User;
}
