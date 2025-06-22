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
        // Create the detail_quiz table
        Schema::create('detail_quiz', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('users_id');
            $table->string('jawaban_user');
            $table->integer('nilai')->nullable(); // nilai per soal (optional)
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('quiz')->onDelete('cascade');
            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
            });
            }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_quiz');
    }
};
