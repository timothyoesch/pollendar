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
        Schema::create('pollen_forecasts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('latitude', 6, 4)->index();
            $table->decimal('longitude', 6, 4)->index();
            $table->integer('tree_upi')->nullable();
            $table->integer('grass_upi')->nullable();
            $table->integer('weed_upi')->nullable();
            $table->json('pollen_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pollen_forecasts');
    }
};
