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
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id('course_video_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('vidio_vidio_id');
            $table->integer('urutan_video');
            $table->integer('durasi_menit')->default(0);
            $table->boolean('is_required')->default(true);
            $table->text('catatan_admin')->nullable();
            
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('section_id')->references('section_id')->on('course_sections')->onDelete('cascade');
            $table->foreign('vidio_vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure unique video order within each section
            $table->unique(['section_id', 'urutan_video']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_videos');
    }
};
