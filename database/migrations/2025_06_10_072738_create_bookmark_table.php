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
        Schema::create('bookmark', function (Blueprint $table) {
            $table->id('bookmark_id');
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('vidio_vidio_id');
            $table->foreign('users_id')->references('users_id')->on('users')->onDelete('cascade');
            $table->foreign('vidio_vidio_id')->references('vidio_id')->on('vidio')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmark');
    }
};
