<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Vidio;
use App\Models\RiwayatTonton;
use Illuminate\Support\Facades\DB;

try {
    // Get test user
    $user = User::where('email', 'customer@test.com')->first();
    if (!$user) {
        echo "❌ Test user tidak ditemukan. Jalankan create_test_data.php terlebih dahulu.\n";
        exit;
    }

    // Get some videos
    $videos = Vidio::take(3)->get();
    if ($videos->count() === 0) {
        echo "❌ Tidak ada video yang ditemukan. Jalankan create_test_data.php terlebih dahulu.\n";
        exit;
    }

    echo "📺 Membuat data riwayat tonton...\n\n";

    foreach ($videos as $index => $video) {
        $watchHistory = RiwayatTonton::recordWatch(
            $user->users_id,
            $video->vidio_id,
            rand(30, 300), // Duration 30-300 seconds
            rand(10, 95)   // Progress 10-95%
        );

        echo "✅ Riwayat tonton untuk video '{$video->nama}' berhasil dibuat\n";
        echo "   - Durasi: {$watchHistory->durasi_tonton} detik\n";
        echo "   - Progress: {$watchHistory->persentase_progress}%\n";
        echo "   - Waktu: {$watchHistory->waktu_ditonton}\n\n";
    }

    // Test API endpoint
    echo "🧪 Testing API Dashboard...\n";

    // Login to get session
    $response = file_get_contents('http://127.0.0.1:8000/api/dashboard', false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: TestScript/1.0',
                'Accept: application/json'
            ]
        ]
    ]));

    if ($response) {
        $data = json_decode($response, true);
        if ($data && isset($data['recently_watched'])) {
            echo "✅ API berhasil mengembalikan data riwayat tonton!\n";
            echo "   - Jumlah riwayat: " . count($data['recently_watched']) . "\n";
        } else {
            echo "⚠️  API berhasil tetapi tidak ada data riwayat tonton\n";
        }
    } else {
        echo "⚠️  Tidak bisa mengakses API (mungkin perlu login dulu)\n";
    }

    echo "\n✅ Setup riwayat tonton selesai!\n";
    echo "🌐 Buka http://127.0.0.1:8000/dashboard untuk melihat hasilnya\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
}
