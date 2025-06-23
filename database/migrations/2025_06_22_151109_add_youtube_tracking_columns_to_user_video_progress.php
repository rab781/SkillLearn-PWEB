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
        Schema::table('user_video_progress', function (Blueprint $table) {
            $table->integer('current_position_seconds')->default(0)->after('watch_time_seconds');
            $table->integer('watched_duration')->default(0)->after('current_position_seconds')->comment('Watched duration in minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_video_progress', function (Blueprint $table) {
            $table->dropColumn(['current_position_seconds', 'watched_duration']);
        });
    }
};
