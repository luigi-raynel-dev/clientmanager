<?php

namespace App\Actions\Projects;

use App\Domain\Projects\Repositories\ProjectRepository;
use App\DTO\Project\ProjectFilter;

class ListProjects
{
  public function __construct(private ProjectRepository $repository) {}

  public function execute(ProjectFilter $filter)
  {
    return $this->repository->search($filter);
  }
}
