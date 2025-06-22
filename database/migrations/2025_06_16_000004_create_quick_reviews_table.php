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
        Schema::create('quick_reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->string('judul_review', 150);
            $table->text('konten_review');
            $table->enum('tipe_review', ['setelah_video', 'setelah_section', 'tengah_course']);
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('vidio_vidio_id')->nullable();
            $table->integer('urutan_review');
            $table->boolean('is_active')->default(true);
            
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('section_id')->references('section_id')->on('course_sections')->onDelete('cascade');
            $table->foreign('vidio_vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_reviews');
    }
};
