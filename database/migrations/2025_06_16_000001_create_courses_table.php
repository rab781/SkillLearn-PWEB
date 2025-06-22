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
        Schema::create('courses', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('nama_course', 150);
            $table->text('deskripsi_course');
            $table->text('gambar_course')->nullable();
            $table->enum('level', ['pemula', 'menengah', 'lanjut'])->default('pemula');
            $table->integer('total_durasi_menit')->default(0);
            $table->integer('total_video')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('kategori_kategori_id');
            $table->foreign('kategori_kategori_id')->references('kategori_id')->on('kategori')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
