<?php

namespace App\DTO\Service;

class ServiceData
{
  public function __construct(
    public string $name,
    public ?string $description = null,
    public ?float $base_price = null,
    public ?string $price_type = null,
    public ?int $estimated_duration_hours = null,
    public ?string $other_price_type = null,
    public ?bool $is_active = true
  ) {}
}
