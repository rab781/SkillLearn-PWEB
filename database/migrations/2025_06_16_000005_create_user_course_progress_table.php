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
        Schema::create('user_course_progress', function (Blueprint $table) {
            $table->id('progress_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('current_section_id')->nullable();
            $table->unsignedBigInteger('current_video_id')->nullable();
            $table->integer('videos_completed')->default(0);
            $table->integer('total_videos');
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            
            $table->foreign('user_id')->references('users_id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('current_section_id')->references('section_id')->on('course_sections')->onDelete('set null');
            $table->foreign('current_video_id')->references('vidio_id')->on('vidio')->onDelete('set null');
            $table->timestamps();
            
            // Ensure one progress record per user per course
            $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_course_progress');
    }
};
