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
        Schema::create('quiz', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('vidio_id');
        $table->text('soal');
        $table->json('pilihan'); // Kolom pilihan bertipe JSON
        $table->text('jawaban_benar');
        $table->timestamps();

        $table->foreign('vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz');
    }
};
