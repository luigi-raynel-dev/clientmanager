<?php

namespace App\Domain\Clients\Repositories;

use App\DTO\Client\ClientData;
use App\DTO\Client\ClientFilter;
use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClientRepository
{
  public function search(ClientFilter $filter): LengthAwarePaginator;

  public function create(ClientData $data): Client;
}
