<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\DTO\Client\ClientFilter;
use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Clients\Repositories\ClientRepository;
use App\DTO\Client\ClientData;

class EloquentClientRepository implements ClientRepository
{
  public function search(ClientFilter $filter): LengthAwarePaginator
  {
    return Client::select('id', 'name', 'email', 'created_at')
      ->when($filter->search, function ($q, $search) {
        $q->where(function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
        });
      })
      ->orderBy($filter->order_by ?? 'created_at', $filter->order_direction ?? 'desc')
      ->paginate($filter->per_page ?? 10);
  }

  public function get(int $id): Client
  {
    return Client::findOrFail($id);
  }

  public function create(ClientData $data): Client
  {
    return Client::create([
      'name' => $data->name,
      'email' => $data->email
    ]);
  }

  public function edit(int $id, ClientData $data): Client
  {
    $user = Client::findOrFail($id);

    $user->name = $data->name;
    $user->email = $data->email;

    $user->save();

    return $user;
  }

  public function delete(int $id): bool
  {
    $client = Client::findOrFail($id);
    return $client->delete();
  }
}
