<?php

// Set proper paths
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SERVER_NAME'] = 'localhost';

// Bootstrap Laravel properly
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

// Make sure we handle the request through the kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/', 'GET');
$response = $kernel->handle($request);
$app->boot();

echo "🚀 Creating additional test data for dashboard...\n\n";

try {
    // Get customer users
    $customers = App\Models\User::where('role', 'CU')->get();
    $videos = App\Models\Vidio::all();

    if ($customers->count() === 0) {
        echo "❌ No customer users found!\n";
        exit;
    }

    if ($videos->count() === 0) {
        echo "❌ No videos found!\n";
        exit;
    }

    echo "📊 Found {$customers->count()} customers and {$videos->count()} videos\n\n";
} catch (Exception $e) {
    echo "❌ Error getting data: " . $e->getMessage() . "\n";
    exit;
}

foreach ($customers as $customer) {
    echo "👤 Creating data for: {$customer->nama_lengkap}\n";

    // Create some bookmarks
    $randomVideos = $videos->random(min(3, $videos->count()));
    foreach ($randomVideos as $video) {
        try {
            App\Models\Bookmark::firstOrCreate([
                'users_id' => $customer->users_id,
                'vidio_vidio_id' => $video->vidio_id
            ]);
            echo "  ⭐ Bookmarked: {$video->nama}\n";
        } catch (Exception $e) {
            // Skip if already exists
        }
    }

    // Create some watch history
    $watchVideos = $videos->random(min(5, $videos->count()));
    foreach ($watchVideos as $index => $video) {
        try {
            App\Models\RiwayatTonton::create([
                'id_pengguna' => $customer->users_id,
                'id_video' => $video->vidio_id,
                'waktu_ditonton' => now()->subHours($index * 2),
                'durasi_tonton' => rand(60, 1800), // 1-30 minutes
                'persentase_progress' => rand(10, 100)
            ]);
            echo "  🕒 Watch history: {$video->nama}\n";
        } catch (Exception $e) {
            // Skip if already exists or unique constraint fails
        }
    }

    // Create some feedback
    $feedbackVideos = $videos->random(min(2, $videos->count()));
    foreach ($feedbackVideos as $video) {
        try {
            App\Models\Feedback::firstOrCreate([
                'users_id' => $customer->users_id,
                'vidio_vidio_id' => $video->vidio_id
            ], [
                'isi_feedback' => 'Great video! Very helpful for learning.',
                'rating' => rand(4, 5),
                'tanggal' => now()
            ]);
            echo "  💬 Feedback: {$video->nama}\n";
        } catch (Exception $e) {
            // Skip if already exists
        }
    }

    echo "\n";
}

echo "✅ Test data creation completed!\n";
echo "🔄 Now refresh the dashboard to see the data.\n";

$kernel->terminate($request, $response);
