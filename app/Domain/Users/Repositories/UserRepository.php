<?php

namespace App\Domain\Users\Repositories;

use App\DTO\User\UserData;
use App\DTO\User\UserFilter;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepository
{
  public function search(UserFilter $filter): LengthAwarePaginator;

  public function get(int $id): User;

  public function findByIds(array $ids): Collection;

  public function create(UserData $data): User;

  public function edit(int $id, UserData $data): User;

  public function delete(int $id): bool;

  public function setIsBlocked(int $id, bool $isBlocked);
}
