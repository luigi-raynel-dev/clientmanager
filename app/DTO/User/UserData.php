<?php

namespace App\DTO\User;

class UserData
{
  public function __construct(
    public string $name,
    public string $email,
    public string $role,
    public ?bool $is_blocked = false,
    public ?string $password = null,
  ) {}
}
