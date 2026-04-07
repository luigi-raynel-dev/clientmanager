<?php

namespace App\Domain\Services\Repositories;

use App\DTO\Service\ServiceData;
use App\DTO\Service\ServiceFilter;
use App\Models\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ServiceRepository
{
  public function search(ServiceFilter $filter): LengthAwarePaginator;

  public function get(int $id): Service;

  public function findByIds(array $ids): Collection;

  public function create(ServiceData $data): Service;

  public function edit(int $id, ServiceData $data): Service;

  public function delete(int $id): bool;

  public function setIsActive(int $id, bool $isActive);
}
