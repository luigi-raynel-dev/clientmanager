<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Models\Service;
use App\Domain\Services\Repositories\ServiceRepository;
use App\DTO\Service\ServiceData;
use App\DTO\Service\ServiceFilter;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentServiceRepository implements ServiceRepository
{
  public function search(ServiceFilter $filter): LengthAwarePaginator
  {
    return Service::select('*')
      ->when($filter->search, function ($q, $search) {
        $q->where(function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
        });
      })
      ->when($filter->is_active !== null, function ($q) use ($filter) {
        $q->where('is_active', $filter->is_active);
      })
      ->orderBy($filter->order_by ?? 'created_at', $filter->order_direction ?? 'desc')
      ->paginate($filter->per_page ?? 10);
  }

  public function get(int $id): Service
  {
    return Service::findOrFail($id);
  }

  public function create(ServiceData $data): Service
  {
    return Service::create([
      'name' => $data->name,
      'description' => $data->description,
      'base_price' => $data->base_price,
      'price_type' => $data->price_type,
      'other_price_type' => $data->other_price_type,
      'estimated_duration_minutes' => $data->estimated_duration_minutes,
      'estimated_duration_type' => $data->estimated_duration_type,
      'is_active' => $data->is_active
    ]);
  }

  public function edit(int $id, ServiceData $data): Service
  {
    $service = Service::findOrFail($id);

    $service->name = $data->name;
    $service->description = $data->description;
    $service->base_price = $data->base_price;
    $service->price_type = $data->price_type;
    $service->other_price_type = $data->other_price_type;
    $service->estimated_duration_minutes = $data->estimated_duration_minutes;
    $service->is_active = $data->is_active;

    $service->save();

    return $service;
  }

  public function setIsActive(int $id, bool $isActive)
  {
    $service = Service::findOrFail($id);
    $service->is_active = $isActive;
    $service->save();
  }

  public function delete(int $id): bool
  {
    $service = Service::findOrFail($id);
    return $service->delete();
  }
}
