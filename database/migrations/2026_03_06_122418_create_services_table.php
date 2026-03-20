<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2)->nullable();
            $table->enum('price_type', ['fixed', 'unit', 'hourly', 'daily'])->nullable();
            $table->string('other_price_type', 255)->nullable();
            $table->decimal('estimated_duration_minutes', 8, 2)->nullable();
            $table->enum('estimated_duration_type', ['minutes', 'hours', 'days', 'weeks', 'months'])->nullable()->after('estimated_duration_minutes');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
