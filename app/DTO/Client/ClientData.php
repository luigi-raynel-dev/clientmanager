<?php

namespace App\DTO\Client;

class ClientData
{
  public function __construct(
    public string $name,
    public string $email
  ) {}
}
