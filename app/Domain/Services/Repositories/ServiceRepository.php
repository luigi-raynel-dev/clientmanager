<?php

namespace App\Domain\Services\Repositories;

use App\DTO\Service\ServiceData;
use App\DTO\Service\ServiceFilter;
use App\Models\Service;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceRepository
{
  public function search(ServiceFilter $filter): LengthAwarePaginator;

  public function create(ServiceData $data): Service;
}
