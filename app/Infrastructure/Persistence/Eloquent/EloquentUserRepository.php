<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Users\Repositories\UserRepository;
use App\DTO\User\UserFilter;
use App\Models\User;
// use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements UserRepository
{
  public function search(UserFilter $filter): LengthAwarePaginator
  {
    return User::select('id', 'name', 'email', 'created_at', 'role')
      ->when($filter->search, function ($q, $search) {
        $q->where(function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
        });
      })
      ->when(
        $filter->role,
        fn($q, $role) =>
        $q->where('role', $role)
      )
      ->orderBy($filter->order_by ?? 'created_at', $filter->order_direction ?? 'desc')
      ->paginate($filter->per_page ?? 10);
  }
}
