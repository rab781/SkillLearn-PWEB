<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing QuizResult query with users_id...\n";

    // Test the QuizResult query
    $result = \App\Models\QuizResult::where('users_id', 1)->first();

    if ($result) {
        echo "Query successful! Found result with quiz_id: " . $result->quiz_id . "\n";
    } else {
        echo "Query successful! No results found for user_id 1\n";
    }

    // Test the basic table query
    $count = \App\Models\QuizResult::count();
    echo "Total QuizResult records: " . $count . "\n";

    // Test User model query
    $user = \App\Models\User::first();
    if ($user) {
        echo "First user found: " . $user->nama_lengkap . " (users_id: " . $user->users_id . ")\n";
    }

    echo "All tests completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
