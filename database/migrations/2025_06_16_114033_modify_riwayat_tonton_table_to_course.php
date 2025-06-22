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
        Schema::table('riwayat_tonton', function (Blueprint $table) {
            // Add course-based columns
            $table->unsignedBigInteger('course_id')->nullable()->after('id_pengguna');
            $table->unsignedBigInteger('course_video_id')->nullable()->after('course_id');
            $table->timestamp('last_watched_at')->nullable()->after('waktu_ditonton');
            $table->decimal('progress_percentage', 5, 2)->default(0)->after('persentase_progress');
            
            // Add foreign key constraints
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('course_video_id')->references('course_video_id')->on('course_videos')->onDelete('cascade');
            
            // Update existing column to be nullable for backward compatibility
            $table->unsignedBigInteger('id_video')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_tonton', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['course_id']);
            $table->dropForeign(['course_video_id']);
            
            // Drop columns
            $table->dropColumn(['course_id', 'course_video_id', 'last_watched_at', 'progress_percentage']);
            
            // Restore id_video to not nullable
            $table->unsignedBigInteger('id_video')->nullable(false)->change();
        });
    }
};
