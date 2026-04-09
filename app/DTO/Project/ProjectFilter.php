<?php

namespace App\DTO\Project;

class ProjectFilter
{
  public function __construct(
    public ?string $search,
    public ?string $priority,
    public ?int $status_id,
    public ?string $order_by,
    public ?string $order_direction,
    public ?int $per_page,
    public ?int $page,
  ) {}
}
