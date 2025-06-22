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
        Schema::create('user_video_progress', function (Blueprint $table) {
            $table->id('video_progress_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vidio_vidio_id');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->integer('watch_time_seconds')->default(0);
            $table->integer('total_duration_seconds')->nullable();
            $table->decimal('completion_percentage', 5, 2)->default(0.00);
            $table->timestamp('first_watched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->foreign('user_id')->references('users_id')->on('users')->onDelete('cascade');
            $table->foreign('vidio_vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure one progress record per user per video per course
            $table->unique(['user_id', 'vidio_vidio_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_video_progress');
    }
};
