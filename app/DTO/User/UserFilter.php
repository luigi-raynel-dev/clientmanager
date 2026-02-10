<?php

namespace App\DTO\User;

class UserFilter
{
  public function __construct(
    public ?string $search,
    public ?string $role
  ) {}
}
