<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            KategoriSeeder::class,
            VidioSeeder::class,
            // Using the new SimpleCourseSeeder instead of CompleteCourseSeeder
            SimpleCourseSeeder::class,
            // Add CourseQuizProgressSeeder for student progress data
            CourseQuizProgressSeeder::class,
        ]);
    }
}
