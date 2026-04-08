<?php

namespace App\DTO\Project;

class ProjectData
{
  public function __construct(
    public string $name,
    public ?string $description = null,
    public ?string $priority = 'Medium',
    public ?int $status_id = null,
    public ?string $start_date = null,
    public ?string $end_date = null,
    public ?float $discount_percentage = null,
    public array $services,
    public array $clients,
    public array $professionals,
  ) {}
}
