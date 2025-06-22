<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::first();
$course = App\Models\Course::first();

if ($user && $course) {
    App\Models\Bookmark::firstOrCreate([
        'users_id' => $user->users_id,
        'course_id' => $course->course_id
    ]);
    echo "Bookmark created successfully for User: {$user->nama_lengkap} and Course: {$course->nama_course}\n";
} else {
    echo "User or Course not found\n";
}
