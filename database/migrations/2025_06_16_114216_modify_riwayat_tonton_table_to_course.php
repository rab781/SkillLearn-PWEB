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
        // Skip this migration since it's trying to add columns that already exist
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed since up() is skipped
    }
};
