<?php

namespace App\Domain\Projects\Repositories;

use App\DTO\Project\ProjectData;
use App\DTO\Project\ProjectFilter;
use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectRepository
{
  public function search(ProjectFilter $filter): LengthAwarePaginator;

  public function create(ProjectData $data): Project;
}
