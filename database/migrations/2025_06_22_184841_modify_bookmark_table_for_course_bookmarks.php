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
        Schema::table('bookmark', function (Blueprint $table) {
            // Drop the existing foreign key constraint and column
            $table->dropForeign(['vidio_vidio_id']);
            $table->dropColumn('vidio_vidio_id');
            
            // Add course_id column with foreign key
            $table->unsignedBigInteger('course_id');
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
            // Drop the unique constraint first
            $table->dropUnique(['users_id', 'course_id']);
            
            // Drop foreign key and course_id column
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
            
            // Add back vidio_vidio_id column with foreign key
            $table->unsignedBigInteger('vidio_vidio_id');
            $table->foreign('vidio_vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
        });
    }
};
