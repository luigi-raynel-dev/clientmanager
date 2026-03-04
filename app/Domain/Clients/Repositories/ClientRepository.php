<?php

namespace App\Domain\Clients\Repositories;

use App\DTO\Client\ClientFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClientRepository
{
  public function search(ClientFilter $filter): LengthAwarePaginator;
}
