<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Models\Project;
use App\Domain\Projects\Repositories\ProjectRepository;
use App\DTO\Project\ProjectData;

class EloquentProjectRepository implements ProjectRepository
{
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
