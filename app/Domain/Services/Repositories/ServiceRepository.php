<?php

namespace App\Domain\Services\Repositories;

use App\DTO\Service\ServiceData;
use App\Models\Service;

interface ServiceRepository
{
  public function create(ServiceData $data): Service;
}
