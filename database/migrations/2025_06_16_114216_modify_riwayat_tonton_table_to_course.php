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
        Schema::table('riwayat_tonton', function (Blueprint $table) {
            // Add course-related columns
            $table->unsignedBigInteger('course_id')->after('id_pengguna');
            $table->unsignedBigInteger('current_video_id')->nullable()->after('course_id');
            $table->integer('video_position')->default(1)->after('current_video_id');
            $table->timestamp('waktu_ditonton')->nullable()->change();
            $table->decimal('persentase_tonton', 5, 2)->default(0)->after('video_position');
            
            // Add foreign key constraints
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->foreign('current_video_id')->references('vidio_id')->on('vidio')->onDelete('set null');
            
            // Remove old video reference if exists
            if (Schema::hasColumn('riwayat_tonton', 'id_video')) {
                $table->dropColumn('id_video');
            }
            
            // Update unique constraint
            $table->unique(['id_pengguna', 'course_id'], 'unique_user_course_history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_tonton', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['current_video_id']);
            $table->dropUnique('unique_user_course_history');
            
            $table->dropColumn(['course_id', 'current_video_id', 'video_position', 'persentase_tonton']);
            
            // Restore old structure
            $table->unsignedBigInteger('id_video')->after('id_pengguna');
            $table->datetime('waktu_ditonton')->change();
        });
    }
};
