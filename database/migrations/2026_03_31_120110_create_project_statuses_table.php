<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#000000');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_final')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Insert default statuses
        DB::table('project_statuses')->insert([
            [
                'title' => 'Not Started',
                'description' => 'The project has not been started yet.',
                'color' => '#FF0000',
                'is_default' => true,
                'is_final' => false,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'On Hold',
                'description' => 'The project is currently on hold.',
                'color' => '#e3c024',
                'is_default' => true,
                'is_final' => false,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'In Progress',
                'description' => 'The project is currently in progress.',
                'color' => '#3f44d4',
                'is_default' => true,
                'is_final' => false,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Completed',
                'description' => 'The project has been completed.',
                'color' => '#00FF00',
                'is_default' => true,
                'is_final' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_statuses');
    }
};
