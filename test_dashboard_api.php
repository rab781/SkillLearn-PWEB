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

// Create a proper request
$request = Illuminate\Http\Request::create('/', 'GET');
$response = $kernel->handle($request);

// Boot the application
$app->boot();

// Now we can use the models
echo "🔍 Testing Dashboard System...\n\n";

// Check if users exist
$users = App\Models\User::all();
echo "📊 Available Users:\n";
foreach ($users as $user) {
    echo "- {$user->nama_lengkap} ({$user->email}) - Role: {$user->role}\n";
}

// Find Mohammad
$user = App\Models\User::where('email', 'mohammad@example.com')->first();
if (!$user) {
    echo "\n❌ Mohammad not found! Looking for any customer user...\n";
    $user = App\Models\User::where('role', 'CU')->first();
}

if (!$user) {
    echo "❌ No customer users found!\n";
    exit;
}

echo "\n✅ Testing with user: {$user->nama_lengkap}\n";

// Simulate authentication
Illuminate\Support\Facades\Auth::login($user);

try {
    // Test the dashboard controller
    $controller = new App\Http\Controllers\DashboardController();
    $response = $controller->customerDashboard();

    echo "\n📊 Dashboard API Test Results:\n";
    echo "Status Code: " . $response->getStatusCode() . "\n";

    $data = json_decode($response->getContent(), true);
    echo "Success: " . ($data['success'] ? '✅ TRUE' : '❌ FALSE') . "\n";

    if ($data['success']) {
        echo "\n📈 Dashboard Data:\n";
        echo "- User: " . $data['user']['name'] . "\n";
        echo "- Email: " . $data['user']['email'] . "\n";
        echo "- Bookmarks: " . $data['stats']['bookmarks_count'] . "\n";
        echo "- Feedback: " . $data['stats']['feedbacks_count'] . "\n";
        echo "- Recent Bookmarks: " . count($data['recent_bookmarks']) . "\n";
        echo "- Recently Watched: " . count($data['recently_watched']) . "\n";
        echo "- Categories: " . count($data['categories']) . "\n";

        if (!empty($data['recently_watched'])) {
            echo "\n🕒 Recently Watched Videos:\n";
            foreach ($data['recently_watched'] as $index => $item) {
                if (isset($item['video'])) {
                    echo "  " . ($index + 1) . ". " . $item['video']['nama'] . "\n";
                }
            }
        }

        if (!empty($data['categories'])) {
            echo "\n🎯 Categories:\n";
            foreach ($data['categories'] as $category) {
                echo "  - " . $category['kategori'] . " (" . ($category['vidios_count'] ?? 0) . " videos)\n";
            }
        }

    } else {
        echo "❌ Error: " . ($data['message'] ?? 'Unknown error') . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error testing dashboard: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

$kernel->terminate($request, $response);
