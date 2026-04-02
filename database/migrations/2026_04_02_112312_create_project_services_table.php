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
        Schema::create('project_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('service_id');
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedBigInteger('pricing_type_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('done')->default(false);
            $table->foreign('pricing_type_id')->references('id')->on('pricing_types')->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_services');
    }
};
