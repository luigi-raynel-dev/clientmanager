<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
  /**
   * Seed the admin user into the database.
   */
  public function run(): void
  {
    Client::factory(20)->create();
  }
}
