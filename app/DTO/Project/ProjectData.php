<?php

namespace App\DTO\Project;

class ProjectData
{
  public function __construct(
    public string $name,
    public ?string $description,
    public ?string $priority = 'Medium',
    public ?int $status_id,
    public ?string $start_date,
    public ?string $end_date,
    public ?float $discount_percentage,
    public array $services,
    public array $clients,
    public array $professionals,
  ) {}
}
