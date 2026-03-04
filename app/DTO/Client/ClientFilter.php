<?php

namespace App\DTO\Client;

class ClientFilter
{
  public function __construct(
    public ?string $search,
    public ?string $order_by,
    public ?string $order_direction,
    public ?int $per_page,
    public ?int $page,
  ) {}
}
