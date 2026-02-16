<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
  /**
   * Seed the admin user into the database.
   */
  public function run(): void
  {
    if (!User::where('email', 'admin@admin.com')->exists()) {
      User::factory()->create([
        'name' => 'admin',
        'email' => 'admin@admin.com',
        'password' => Hash::make('admin'),
        'role' => 'admin'
      ]);
    } else $this->command->info('Admin user already exists, skipping creation.');
  }
}
