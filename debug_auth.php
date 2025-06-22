<?php

// Debug authentication
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Start session
session_start();

echo "<h2>Debug Authentication</h2>";
echo "<h3>Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>PHP Session ID:</h3>";
echo session_id();

echo "<h3>Laravel Session:</h3>";
if (isset($_SESSION['laravel_session'])) {
    echo "Laravel session exists<br>";
} else {
    echo "No Laravel session found<br>";
}

echo "<h3>Cookies:</h3>";
echo "<pre>";
print_r($_COOKIE);
echo "</pre>";

echo "<h3>Current URL:</h3>";
echo $_SERVER['REQUEST_URI'] ?? 'Not set';

echo "<h3>Test Auth Check:</h3>";
// We'll need to bootstrap Laravel properly for this
try {
    $app = new Illuminate\Foundation\Application(
        $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
    );
    
    echo "Laravel app created successfully<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
