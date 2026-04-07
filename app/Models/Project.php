<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

# Aggregate Root
class Project extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'description',
    'start_date',
    'end_date',
    'discount_percentage',
    'priority',
    'status_id'
  ];

  public function status()
  {
    return $this->belongsTo(ProjectStatus::class);
  }

  public function services()
  {
    return $this->belongsToMany(Service::class, 'project_services', 'project_id', 'service_id');
  }

  public function clients()
  {
    return $this->belongsToMany(Client::class, 'project_clients', 'project_id', 'client_id');
  }

  public function professionals()
  {
    return $this->belongsToMany(User::class, 'project_professionals', 'project_id', 'professional_id');
  }

  public function addServices(Collection $services): void
  {
    $this->services()->syncWithoutDetaching($services);
  }

  public function addClients(Collection $clients): void
  {
    if ($clients->isEmpty()) {
      throw new \DomainException('Project must have at least one client');
    }

    $this->clients()->syncWithoutDetaching($clients);
  }

  public function addProfessionals(Collection $professionals): void
  {
    $this->professionals()->syncWithoutDetaching($professionals);
  }
}
