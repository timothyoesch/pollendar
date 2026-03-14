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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('homebase_latitude', 6, 4)->nullable();
            $table->decimal('homebase_longitude', 6, 4)->nullable();
            $table->string('homebase_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['homebase_latitude', 'homebase_longitude', 'homebase_name']);
        });
    }
};
