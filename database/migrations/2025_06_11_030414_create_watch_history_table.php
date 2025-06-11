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
        Schema::create('riwayat_tonton', function (Blueprint $table) {
            $table->id('id_riwayat_tonton');
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_video');
            $table->timestamp('waktu_ditonton');
            $table->integer('durasi_tonton')->default(0); // Durasi dalam detik
            $table->decimal('persentase_progress', 5, 2)->default(0.00); // Progress dalam persentase
            $table->foreign('id_pengguna')->references('users_id')->on('users')->onDelete('cascade');
            $table->foreign('id_video')->references('vidio_id')->on('vidio')->onDelete('cascade');
            $table->timestamps();

            // Pastikan satu record per user-video combination per hari
            $table->unique(['id_pengguna', 'id_video', 'waktu_ditonton'], 'unique_pengguna_video_tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_tonton');
    }
};
