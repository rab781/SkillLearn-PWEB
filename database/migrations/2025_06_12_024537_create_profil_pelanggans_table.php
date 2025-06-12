<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('no_telepon', 12)->unique();
            $table->string('username', 84)->unique();
            $table->string('email', 84)->unique();
            $table->string('alamat')->nullable();
            $table->string('password', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_pelanggans');
    }
};
