<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RiwayatTonton;
use App\Models\User;
use App\Models\Vidio;
use App\Models\Course;
use App\Models\UserCourseProgress;

class SampleMonitoringDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang diperlukan
        $users = User::where('role', 'CU')->take(3)->get();
        $videos = Vidio::take(5)->get();
        $courses = Course::take(3)->get();

        if ($users->isEmpty() || $videos->isEmpty() || $courses->isEmpty()) {
            echo "Warning: Not enough users, videos, or courses found\n";
            return;
        }

        // Buat sample data RiwayatTonton
        foreach ($users as $user) {
            foreach ($courses->take(2) as $course) {
                // Buat user course progress
                UserCourseProgress::updateOrCreate([
                    'user_id' => $user->users_id,
                    'course_id' => $course->course_id,
                ], [
                    'status' => rand(0, 1) ? 'in_progress' : 'completed',
                    'progress_percentage' => rand(20, 100),
                    'last_accessed_at' => now()->subDays(rand(0, 7))
                ]);

                // Buat beberapa riwayat tonton
                foreach ($videos->take(3) as $video) {
                    RiwayatTonton::create([
                        'id_pengguna' => $user->users_id,
                        'id_video' => $video->vidio_id,
                        'course_id' => $course->course_id,
                        'waktu_ditonton' => now()->subHours(rand(1, 48)),
                        'durasi_tonton' => rand(60, 1800), // 1-30 menit
                        'persentase_progress' => rand(10, 100),
                        'progress_percentage' => rand(10, 100)
                    ]);
                }
            }
        }

        echo "Sample monitoring data created successfully\n";
    }
}
