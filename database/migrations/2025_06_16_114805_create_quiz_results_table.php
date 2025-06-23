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
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id('result_quiz_id');
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('users_id');
            $table->decimal('nilai_total', 5, 2);
            $table->integer('jumlah_benar')->default(0);
            $table->integer('jumlah_salah')->default(0);
            $table->integer('total_soal')->default(0);
            $table->json('detail_jawaban')->nullable(); // Untuk menyimpan detail jawaban user
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();

            $table->foreign('quiz_id')->references('quiz_id')->on('quizzes')->onDelete('cascade');
            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
            $table->unique(['quiz_id', 'users_id'], 'unique_user_quiz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};
