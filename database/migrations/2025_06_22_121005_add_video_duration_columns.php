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
        // Periksa apakah kolom sudah ada
        if (!Schema::hasColumn('vidio', 'durasi_menit')) {
            Schema::table('vidio', function (Blueprint $table) {
                $table->integer('durasi_menit')->default(0)->after('kategori_kategori_id');
            });
        }

        if (!Schema::hasColumn('vidio', 'is_active')) {
            Schema::table('vidio', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('channel');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vidio', function (Blueprint $table) {
            $table->dropColumn(['durasi_menit', 'is_active']);
        });
    }
};
