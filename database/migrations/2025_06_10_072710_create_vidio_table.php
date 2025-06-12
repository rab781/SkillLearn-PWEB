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
        Schema::create('vidio', function (Blueprint $table) {
            $table->id('vidio_id');
            $table->string('nama', 120);
            $table->text('deskripsi');
            $table->text('url');
            $table->text('gambar');
            $table->integer('jumlah_tayang')->default(0);
            $table->unsignedBigInteger('kategori_kategori_id');
            $table->foreign('kategori_kategori_id')->references('kategori_id')->on('kategori')->onDelete('cascade');
            $table->timestamps();
            $table->text('channel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vidio');
    }
};
