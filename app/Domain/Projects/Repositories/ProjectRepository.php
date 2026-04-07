<?php

namespace App\Domain\Projects\Repositories;

use App\DTO\Project\ProjectData;
use App\Models\Project;

interface ProjectRepository
{
  public function create(ProjectData $data): Project;
}
