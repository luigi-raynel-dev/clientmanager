<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Users\Repositories\UserRepository;
use App\DTO\User\UserData;
use App\DTO\User\UserFilter;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

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

  public function create(UserData $data): User
  {
    return User::create([
      'name' => $data->name,
      'email' => $data->email,
      'password' => Hash::make($data->password),
      'role' => $data->role,
    ]);
  }

  public function edit(int $id, UserData $data): User
  {
    $user = User::findOrFail($id);

    $user->name = $data->name;
    $user->email = $data->email;
    $user->role = $data->role;

    $user->save();

    return $user;
  }
}
