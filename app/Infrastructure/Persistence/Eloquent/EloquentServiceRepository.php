<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Models\Service;
use App\Domain\Services\Repositories\ServiceRepository;
use App\DTO\Service\ServiceData;
use App\DTO\Service\ServiceFilter;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
      ->with('pricingType')
      ->paginate($filter->per_page ?? 10);
  }

  public function list(): Collection
  {
    return Service::where('is_active', 1)
      ->with('pricingType')
      ->get();
  }

  public function get(int $id): Service
  {
    return Service::with('pricingType')->findOrFail($id);
  }

  public function findByIds(array $ids): Collection
  {
    return Service::whereIn('id', $ids)->get();
  }

  public function create(ServiceData $data): Service
  {
    return Service::create([
      'name' => $data->name,
      'description' => $data->description,
      'base_price' => $data->base_price,
      'pricing_type_id' => $data->pricing_type_id,
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
    $service->pricing_type_id = $data->pricing_type_id;
    $service->estimated_duration_minutes = $data->estimated_duration_minutes;
    $service->estimated_duration_type = $data->estimated_duration_type;
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
