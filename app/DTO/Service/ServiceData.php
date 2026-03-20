<?php

namespace App\DTO\Service;

class ServiceData
{
  public function __construct(
    public string $name,
    public ?string $description = null,
    public ?float $base_price = null,
    public ?string $price_type = null,
    public ?int $estimated_duration_minutes = null,
    public ?string $estimated_duration_type = null,
    public ?string $other_price_type = null,
    public ?bool $is_active = true
  ) {}
}
