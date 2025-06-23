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
        Schema::create('course_quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('quiz_id');
            $table->enum('position', ['start', 'end', 'after_section', 'after_video', 'between_sections']);
            $table->unsignedBigInteger('reference_id')->nullable(); // section_id or course_video_id
            $table->integer('order')->default(1);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('quiz_id')->references('quiz_id')->on('quizzes')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate quiz in same course
            $table->unique(['course_id', 'quiz_id']);
            
            // Index for ordering
            $table->index(['course_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_quizzes');
    }
};
