<?php

use App\Actions\Projects\CreateProject;
use App\DTO\Project\ProjectData;
use App\Infrastructure\Persistence\Eloquent\EloquentProjectRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentServiceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentClientRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use App\Models\Project;
use App\Models\Service;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a project with relationships', function () {

  // Aggregate related roots (factories)
  $services = Service::factory()->count(2)->create();
  $clients = Client::factory()->count(1)->create();
  $professionals = User::factory()->count(2)->create();

  // DTO
  $data = new ProjectData(
    name: fake()->sentence(3),
    description: fake()->paragraph(),
    status_id: fake()->randomElement([1, 2, 3]),
    priority: fake()->randomElement(['Low', 'Medium', 'High']),
    start_date: now(),
    end_date: now()->addDays(10),
    discount_percentage: fake()->randomFloat(2, 0, 100),
    services: $services->pluck('id')->toArray(),
    clients: $clients->pluck('id')->toArray(),
    professionals: $professionals->pluck('id')->toArray(),
  );

  // Action
  $action = new CreateProject(
    new EloquentProjectRepository(),
    new EloquentServiceRepository(),
    new EloquentClientRepository(),
    new EloquentUserRepository(),
  );

  $project = $action->execute($data);

  // Assertions

  expect($project)->toBeInstanceOf(Project::class);

  $this->assertDatabaseHas('projects', [
    'id' => $project->id,
    'name' => $data->name,
    'description' => $data->description,
    'status_id' => $data->status_id,
    'priority' => $data->priority,
    'start_date' => $data->start_date,
    'end_date' => $data->end_date,
    'discount_percentage' => $data->discount_percentage,
  ]);

  // Related (pivot tables)
  foreach ($services as $service) {
    $this->assertDatabaseHas('project_services', [
      'project_id' => $project->id,
      'service_id' => $service->id,
    ]);
  }

  foreach ($clients as $client) {
    $this->assertDatabaseHas('project_clients', [
      'project_id' => $project->id,
      'client_id' => $client->id,
    ]);
  }

  foreach ($professionals as $professional) {
    $this->assertDatabaseHas('project_professionals', [
      'project_id' => $project->id,
      'professional_id' => $professional->id,
    ]);
  }
});
