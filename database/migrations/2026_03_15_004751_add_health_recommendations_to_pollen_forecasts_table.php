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
        Schema::table('pollen_forecasts', function (Blueprint $table) {
            $table->string('health_recommendations')->nullable()->after('weed_upi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pollen_forecasts', function (Blueprint $table) {
            $table->dropColumn('health_recommendations');
        });
    }
};
