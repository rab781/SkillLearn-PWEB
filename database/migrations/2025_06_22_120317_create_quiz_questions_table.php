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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id('question_id');
            $table->unsignedBigInteger('quiz_id');
            $table->integer('urutan_pertanyaan');
            $table->text('pertanyaan');
            $table->text('pilihan_jawaban'); // JSON format untuk pilihan
            $table->text('jawaban_benar');
            $table->integer('bobot_nilai')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('quiz_id')->references('quiz_id')->on('quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
