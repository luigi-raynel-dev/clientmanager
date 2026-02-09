<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Users\Repositories\UserRepository;
use App\DTO\User\UserFilter;
use App\Models\User;
use Illuminate\Support\Collection;

class EloquentUserRepository implements UserRepository
{
  public function search(UserFilter $filter): Collection
  {
    return User::select('id', 'name', 'email', 'created_at', 'role')
      ->when(
        $filter->search,
        fn($q, $search) =>
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%")
      )
      ->orderByDesc('created_at')
      ->get();
  }
}
