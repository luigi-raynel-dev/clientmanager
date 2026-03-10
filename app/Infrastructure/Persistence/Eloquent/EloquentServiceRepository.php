<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Models\Service;
use App\Domain\Services\Repositories\ServiceRepository;
use App\DTO\Service\ServiceData;

class EloquentServiceRepository implements ServiceRepository
{
  public function create(ServiceData $data): Service
  {
    return Service::create([
      'name' => $data->name,
      'description' => $data->description,
      'base_price' => $data->base_price,
      'price_type' => $data->price_type,
      'other_price_type' => $data->other_price_type,
      'estimated_duration_hours' => $data->estimated_duration_hours,
      'is_active' => $data->is_active
    ]);
  }
}
