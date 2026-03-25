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
        Schema::create('pricing_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table('pricing_types')->insert([
            ['name' => 'unit'],
            ['name' => 'hourly'],
            ['name' => 'daily'],
        ]);

        Schema::table('services', function (Blueprint $table) {
            $table->unsignedBigInteger('pricing_type_id')->nullable()->after('base_price');
            $table->foreign('pricing_type_id')->references('id')->on('pricing_types')->onDelete('set null');
        });

        $services = DB::table('services')->whereNotNull('price_type')->get()->toArray();

        foreach ($services as $service) {
            if ($service->price_type === 'fixed') continue;

            $pricingType = DB::table('pricing_types')->where('name', $service->price_type)->first();
            if ($pricingType) {
                DB::table('services')->where('id', $service->id)->update(['pricing_type_id' => $pricingType->id]);
            }
        }

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['price_type', 'other_price_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['pricing_type_id']);
            $table->dropColumn('pricing_type_id');
            $table->enum('price_type', ['fixed', 'unit', 'hourly', 'daily'])->nullable();
            $table->string('other_price_type', 255)->nullable();
        });
        Schema::dropIfExists('pricing_types');
    }
};
