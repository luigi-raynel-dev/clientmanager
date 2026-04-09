<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
  /**
   * Seed the admin user into the database.
   */
  public function run(): void
  {
    Project::factory(10)->create();
  }
}
