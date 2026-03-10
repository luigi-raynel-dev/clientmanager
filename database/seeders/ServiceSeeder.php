<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
  /**
   * Seed the admin user into the database.
   */
  public function run(): void
  {
    Service::factory(10)->create();
  }
}
