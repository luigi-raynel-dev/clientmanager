<?php

namespace App\Domain\Clients\Repositories;

use App\DTO\Client\ClientData;
use App\DTO\Client\ClientFilter;
use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ClientRepository
{
  public function search(ClientFilter $filter): LengthAwarePaginator;

  public function get(int $id): Client;

  public function findByIds(array $ids): Collection;

  public function create(ClientData $data): Client;

  public function edit(int $id, ClientData $data): Client;

  public function delete(int $id): bool;
}
