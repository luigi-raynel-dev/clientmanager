<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Models\Project;
use App\Domain\Projects\Repositories\ProjectRepository;
use App\DTO\Project\ProjectData;
use App\DTO\Project\ProjectFilter;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProjectRepository implements ProjectRepository
{
  public function search(ProjectFilter $filter): LengthAwarePaginator
  {
    return Project::select('*')
      ->when($filter->search, function ($q, $search) {
        $q->where(function ($q) use ($search) {
          $q->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
        });
      })
      ->when($filter->priority !== null, function ($q) use ($filter) {
        $q->where('priority', $filter->priority);
      })
      ->when($filter->status_id !== null, function ($q) use ($filter) {
        $q->where('status_id', $filter->status_id);
      })
      ->when(
        !$filter->order_by,
        fn($q) => $q->orderByRaw('status_id = ? desc', [2])
          ->orderBy('priority', 'desc')
          ->orderBy('updated_at', 'desc')
      )
      ->when($filter->order_by != null, function ($q) use ($filter) {
        $q->orderBy($filter->order_by, $filter->order_direction ?? "asc");
      })
      ->with(['services', 'services.pricingType', 'status'])
      ->paginate($filter->per_page ?? 25);
  }

  public function create(ProjectData $data): Project
  {
    return Project::create([
      'name' => $data->name,
      'description' => $data->description,
      'start_date' => $data->start_date,
      'end_date' => $data->end_date,
      'discount_percentage' => $data->discount_percentage,
      'priority' => $data->priority,
      'status_id' => $data->status_id
    ]);
  }
}
