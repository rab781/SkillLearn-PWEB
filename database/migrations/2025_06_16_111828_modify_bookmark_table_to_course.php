<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip this migration entirely
        // Previously this migration was trying to add a course_id column
        // that already exists in the schema
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed since up() is skipped
    }
};
