<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Create application instance
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🧪 Testing Registration Process...\n\n";

// Test data
$testData = [
    'nama_lengkap' => 'Test User Registration',
    'username' => 'testuser' . rand(1000, 9999),
    'email' => 'testuser' . rand(1000, 9999) . '@example.com',
    'no_telepon' => '08123456' . rand(100, 999),
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

echo "📝 Test Data:\n";
foreach ($testData as $key => $value) {
    if ($key === 'password' || $key === 'password_confirmation') {
        echo "   {$key}: " . str_repeat('*', strlen($value)) . "\n";
    } else {
        echo "   {$key}: {$value}\n";
    }
}
echo "\n";

try {
    // Create request
    $request = Request::create('/api/register', 'POST', $testData);
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('Content-Type', 'application/json');

    // Process request
    $response = $kernel->handle($request);

    echo "📡 API Response:\n";
    echo "   Status Code: " . $response->getStatusCode() . "\n";
    echo "   Content: " . $response->getContent() . "\n\n";

    $responseData = json_decode($response->getContent(), true);

    if (isset($responseData['success']) && $responseData['success']) {
        echo "✅ Registration Test: PASSED\n";
        echo "   Message: " . $responseData['message'] . "\n";
        if (isset($responseData['user'])) {
            echo "   User ID: " . $responseData['user']['users_id'] . "\n";
            echo "   User Role: " . $responseData['user']['role'] . "\n";
        }
    } else {
        echo "❌ Registration Test: FAILED\n";
        echo "   Message: " . ($responseData['message'] ?? 'Unknown error') . "\n";
        if (isset($responseData['errors'])) {
            echo "   Errors:\n";
            foreach ($responseData['errors'] as $field => $errors) {
                foreach ($errors as $error) {
                    echo "     - {$field}: {$error}\n";
                }
            }
        }
    }

} catch (Exception $e) {
    echo "❌ Registration Test: ERROR\n";
    echo "   Exception: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n🔍 Testing Registration Form Validation...\n";

// Test invalid data
$invalidTests = [
    'missing_name' => array_merge($testData, ['nama_lengkap' => '']),
    'invalid_email' => array_merge($testData, ['email' => 'invalid-email']),
    'short_password' => array_merge($testData, ['password' => '123', 'password_confirmation' => '123']),
    'password_mismatch' => array_merge($testData, ['password_confirmation' => 'different']),
];

foreach ($invalidTests as $testName => $data) {
    try {
        $request = Request::create('/api/register', 'POST', $data);
        $request->headers->set('Accept', 'application/json');
        $response = $kernel->handle($request);
        $responseData = json_decode($response->getContent(), true);

        if ($response->getStatusCode() === 422) {
            echo "✅ Validation Test ({$testName}): PASSED - Correctly rejected\n";
        } else {
            echo "❌ Validation Test ({$testName}): FAILED - Should have been rejected\n";
        }
    } catch (Exception $e) {
        echo "❌ Validation Test ({$testName}): ERROR - " . $e->getMessage() . "\n";
    }
}

echo "\n✨ Registration Test Complete!\n";
