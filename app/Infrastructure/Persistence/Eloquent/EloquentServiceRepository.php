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
      ->orderBy($filter->order_by ?? 'created_at', $filter->order_direction ?? 'desc')
      ->paginate($filter->per_page ?? 10);
  }

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
