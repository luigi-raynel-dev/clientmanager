<?php

namespace App\Actions\Projects;

use App\Domain\Clients\Repositories\ClientRepository;
use App\Domain\Projects\Repositories\ProjectRepository;
use App\Domain\Services\Repositories\ServiceRepository;
use App\Domain\Users\Repositories\UserRepository;
use App\DTO\Project\ProjectData;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
  public function __construct(
    private ProjectRepository $projectRepository,
    private ServiceRepository $serviceRepository,
    private ClientRepository $clientRepository,
    private UserRepository $professionalRepository,
  ) {}

  public function execute(ProjectData $data): Project
  {
    DB::beginTransaction();

    try {
      // Create the project
      $project = $this->projectRepository->create($data);

      // Get related entities
      $services = $this->serviceRepository->findByIds($data->services);
      $clients = $this->clientRepository->findByIds($data->clients);
      $professionals = $this->professionalRepository->findByIds($data->professionals);

      // Aggregate
      $project->addServices($services);
      $project->addClients($clients);
      $project->addProfessionals($professionals);

      DB::commit();

      return $project;
    } catch (\Throwable $e) {
      DB::rollBack();
      throw $e;
    }
  }
}
