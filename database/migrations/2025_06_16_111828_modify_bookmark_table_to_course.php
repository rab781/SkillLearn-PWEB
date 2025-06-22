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
        // First, clear existing bookmarks to avoid foreign key issues
        DB::table('bookmark')->truncate();
        
        Schema::table('bookmark', function (Blueprint $table) {
            // Drop foreign key and column for video
            $table->dropForeign(['vidio_vidio_id']);
            $table->dropColumn('vidio_vidio_id');
            
            // Add column for course
            $table->unsignedBigInteger('course_id')->after('users_id');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            
            // Add unique constraint to prevent duplicate bookmarks
            $table->unique(['users_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookmark', function (Blueprint $table) {
            // Drop foreign key and column for course
            $table->dropForeign(['course_id']);
            $table->dropUnique(['users_id', 'course_id']);
            $table->dropColumn('course_id');
            
            // Add back column for video
            $table->unsignedBigInteger('vidio_vidio_id')->after('users_id');
            $table->foreign('vidio_vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
        });
    }
};
