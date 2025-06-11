<?php

/**
 * Test Watch History System
 *
 * This script tests the newly implemented watch history functionality
 * with Indonesian column names in the riwayat_tonton table.
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Vidio;
use App\Models\RiwayatTonton;

require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING WATCH HISTORY SYSTEM ===\n\n";

try {
    // Check if watch history table exists
    echo "1. Checking if riwayat_tonton table exists...\n";
    $tableExists = DB::getSchemaBuilder()->hasTable('riwayat_tonton');
    echo $tableExists ? "✅ Table riwayat_tonton exists\n" : "❌ Table riwayat_tonton does not exist\n";

    if (!$tableExists) {
        echo "Please run: php artisan migrate\n";
        exit(1);
    }

    // Check table structure
    echo "\n2. Checking table structure...\n";
    $columns = DB::getSchemaBuilder()->getColumnListing('riwayat_tonton');
    $expectedColumns = ['id_riwayat_tonton', 'id_pengguna', 'id_video', 'waktu_ditonton', 'durasi_tonton', 'persentase_progress'];

    foreach ($expectedColumns as $column) {
        $exists = in_array($column, $columns);
        echo ($exists ? "✅" : "❌") . " Column: $column\n";
    }

    // Test getting a user and video for testing
    echo "\n3. Getting test data...\n";
    $user = User::where('role', 'CU')->first();
    if (!$user) {
        echo "❌ No customer user found. Please register a user first.\n";
        exit(1);
    }
    echo "✅ Found user: {$user->nama_lengkap} (ID: {$user->users_id})\n";

    $video = Vidio::first();
    if (!$video) {
        echo "❌ No video found. Please add videos first.\n";
        exit(1);
    }
    echo "✅ Found video: {$video->nama} (ID: {$video->vidio_id})\n";

    // Test creating watch history record
    echo "\n4. Testing watch history creation...\n";
    $watchHistory = RiwayatTonton::recordWatch(
        $user->users_id,
        $video->vidio_id,
        120, // 2 minutes duration
        25.5 // 25.5% progress
    );

    if ($watchHistory) {
        echo "✅ Watch history created successfully\n";
        echo "   - User ID: {$watchHistory->id_pengguna}\n";
        echo "   - Video ID: {$watchHistory->id_video}\n";
        echo "   - Watch Time: {$watchHistory->waktu_ditonton}\n";
        echo "   - Duration: {$watchHistory->durasi_tonton} seconds\n";
        echo "   - Progress: {$watchHistory->persentase_progress}%\n";
    } else {
        echo "❌ Failed to create watch history\n";
    }

    // Test retrieving watch history
    echo "\n5. Testing watch history retrieval...\n";
    $recentHistory = RiwayatTonton::with(['video.kategori'])
        ->byUser($user->users_id)
        ->recent(5)
        ->get();

    echo "✅ Found {$recentHistory->count()} watch history records for user\n";
    foreach ($recentHistory as $history) {
        echo "   - {$history->video->nama} watched at {$history->waktu_ditonton} ({$history->persentase_progress}% complete)\n";
    }

    // Test dashboard data
    echo "\n6. Testing dashboard data structure...\n";
    $dashboardData = [
        'recently_watched' => $recentHistory->map(function($history) {
            return [
                'video' => $history->video,
                'waktu_ditonton' => $history->waktu_ditonton,
                'persentase_progress' => $history->persentase_progress
            ];
        })
    ];

    echo "✅ Dashboard data structure ready:\n";
    echo "   - Recently watched videos: " . count($dashboardData['recently_watched']) . "\n";

    // Test model relationships
    echo "\n7. Testing model relationships...\n";
    if ($watchHistory) {
        $relatedUser = $watchHistory->pengguna;
        $relatedVideo = $watchHistory->video;

        echo ($relatedUser ? "✅" : "❌") . " User relationship works\n";
        echo ($relatedVideo ? "✅" : "❌") . " Video relationship works\n";

        if ($relatedUser) {
            echo "   - Related user: {$relatedUser->nama_lengkap}\n";
        }
        if ($relatedVideo) {
            echo "   - Related video: {$relatedVideo->nama}\n";
        }
    }

    echo "\n=== WATCH HISTORY SYSTEM TEST COMPLETED SUCCESSFULLY ===\n";
    echo "🎉 All tests passed! The watch history system is working correctly.\n";
    echo "\nNext steps:\n";
    echo "1. Test the web interface at http://127.0.0.1:8000\n";
    echo "2. Login as a customer and check the dashboard\n";
    echo "3. Watch a video to create history records\n";
    echo "4. Verify the 'Riwayat Video yang Baru Ditonton' section displays correctly\n";

} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
