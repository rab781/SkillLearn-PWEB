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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id('quiz_id');
            $table->string('judul_quiz', 150);
            $table->text('deskripsi_quiz')->nullable();
            $table->enum('tipe_quiz', ['setelah_video', 'setelah_section', 'tengah_course', 'akhir_course']);
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('vidio_vidio_id')->nullable();
            $table->integer('urutan')->default(0);
            $table->integer('durasi_menit')->default(15);
            $table->text('konten_quiz')->nullable();
            $table->text('soal')->nullable(); // Backward compatibility
            $table->text('jawaban')->nullable(); // Backward compatibility
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('section_id')->references('section_id')->on('course_sections')->onDelete('cascade');
            $table->foreign('vidio_vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
            $table->index(['course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
