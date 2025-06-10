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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->date('tanggal');
            $table->text('pesan');
            $table->text('balasan')->nullable();
            $table->unsignedBigInteger('vidio_vidio_id');
            $table->unsignedBigInteger('users_id');
            $table->foreign('vidio_vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
