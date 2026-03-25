<?php

namespace App\DTO\Service;

class ServiceData
{
  public function __construct(
    public string $name,
    public ?string $description = null,
    public ?float $base_price = null,
    public ?int $pricing_type_id = null,
    public ?int $estimated_duration_minutes = null,
    public ?string $estimated_duration_type = null,
    public ?bool $is_active = true
  ) {}
}
