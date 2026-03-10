<?php

namespace App\DTO\Service;

class ServiceData
{
  public function __construct(
    public string $name,
    public string $description,
    public float $base_price,
    public string $price_type,
    public int $estimated_duration_hours,
    public ?string $other_price_type = null,
    public ?bool $is_active = true
  ) {}
}
