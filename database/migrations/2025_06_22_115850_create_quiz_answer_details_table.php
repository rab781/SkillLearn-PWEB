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
        Schema::create('quiz_answer_details', function (Blueprint $table) {
            $table->id('answer_detail_id');
            $table->unsignedBigInteger('result_quiz_id');
            $table->unsignedBigInteger('quiz_id');
            $table->integer('urutan_pertanyaan');
            $table->text('pertanyaan');
            $table->text('jawaban_user');
            $table->text('jawaban_benar');
            $table->boolean('is_correct')->default(false);
            $table->integer('skor_pertanyaan')->default(0);
            $table->timestamps();

            $table->foreign('result_quiz_id')->references('result_quiz_id')->on('quiz_results')->onDelete('cascade');
            $table->foreign('quiz_id')->references('quiz_id')->on('quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answer_details');
    }
};
