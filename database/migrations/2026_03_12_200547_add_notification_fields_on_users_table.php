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
            $table->string('timezone')->default('Europe/Zurich');
            $table->time('notification_time')->default('20:00:00');
            $table->timestamp('last_notified_at')->nullable();
            $table->integer('max_notification_attempts')->default(2);
            $table->integer('notification_attempts_today')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['timezone', 'notification_time', 'last_notified_at', 'max_notification_attempts', 'notification_attempts_today']);
        });
    }
};
