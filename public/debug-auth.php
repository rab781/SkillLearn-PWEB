<?php
// Simple debug script to check authentication status
session_start();

// Include Laravel bootstrap
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: application/json');

$response = [
    'timestamp' => now()->toISOString(),
    'session_id' => session_id(),
    'session_data' => session()->all(),
    'auth_check' => auth()->check(),
    'auth_user' => auth()->user(),
    'csrf_token' => csrf_token(),
    'app_env' => config('app.env'),
    'app_debug' => config('app.debug'),
    'session_driver' => config('session.driver'),
    'session_lifetime' => config('session.lifetime'),
];

echo json_encode($response, JSON_PRETTY_PRINT);
