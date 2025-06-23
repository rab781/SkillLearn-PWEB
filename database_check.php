<?php

// Load Laravel environment
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get database connections
$connection = \Illuminate\Support\Facades\DB::connection();
$pdo = $connection->getPdo();

// Check the tables structure
$tables = [
    'bookmark',
    'feedback',
    'quiz_results',
    'user_video_progress',
    'user_course_progress'
];

foreach ($tables as $table) {
    try {
        echo "Table: {$table}\n";
        echo "=================\n";
        $stmt = $pdo->query("DESCRIBE {$table}");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($columns as $column) {
            echo "{$column['Field']} | {$column['Type']} | {$column['Null']} | {$column['Key']} | {$column['Default']}\n";
        }
        echo "\n\n";
    } catch (Exception $e) {
        echo "Error checking table {$table}: " . $e->getMessage() . "\n\n";
    }
}
