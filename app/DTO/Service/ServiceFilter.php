<?php

namespace App\DTO\Service;

class ServiceFilter
{
  public function __construct(
    public ?string $search,
    public ?bool $is_active,
    public ?string $order_by,
    public ?string $order_direction,
    public ?int $per_page,
    public ?int $page,
  ) {}
}
