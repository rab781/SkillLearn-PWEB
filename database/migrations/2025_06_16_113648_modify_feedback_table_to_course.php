<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear existing feedback data to avoid foreign key issues
        DB::table('feedback')->truncate();
        
        Schema::table('feedback', function (Blueprint $table) {
            // Drop foreign key and column for video
            $table->dropForeign(['vidio_vidio_id']);
            $table->dropColumn('vidio_vidio_id');
            
            // Add columns for course-based feedback
            $table->unsignedBigInteger('course_id')->after('users_id');
            $table->unsignedBigInteger('course_video_id')->nullable()->after('course_id');
            $table->enum('rating', [1, 2, 3, 4, 5])->nullable()->after('pesan');
            $table->text('catatan')->nullable()->after('rating'); // Personal notes
            
            // Add foreign keys
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('course_video_id')->references('course_video_id')->on('course_videos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Drop new columns and foreign keys
            $table->dropForeign(['course_id']);
            $table->dropForeign(['course_video_id']);
            $table->dropColumn(['course_id', 'course_video_id', 'rating', 'catatan']);
            
            // Add back video column
            $table->unsignedBigInteger('vidio_vidio_id')->after('users_id');
            $table->foreign('vidio_vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
        });
    }
};
