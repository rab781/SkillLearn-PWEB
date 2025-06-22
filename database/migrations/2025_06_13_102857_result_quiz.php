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
        Schema::create('result_quiz', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('vidio_id');
    $table->unsignedBigInteger('users_id');
    $table->integer('nilai_total');
    $table->timestamps();

    $table->foreign('vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
    $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_quiz');
    }
};
