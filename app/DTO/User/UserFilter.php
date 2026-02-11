<?php

namespace App\DTO\User;

class UserFilter
{
  public function __construct(
    public ?string $search,
    public ?string $role,
    public ?string $order_by,
    public ?string $order_direction
  ) {}
}
