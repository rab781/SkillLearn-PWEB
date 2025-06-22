<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;

echo "=== CHECKING AND CREATING ADMIN USER ===\n\n";

// Check if admin user exists
$adminUser = User::where('email', 'admin@skilllearn.com')->first();

if (!$adminUser) {
    echo "Creating admin user...\n";
    $adminUser = User::create([
        'username' => 'admin',
        'email' => 'admin@skilllearn.com',
        'nama_lengkap' => 'Admin SkillLearn',
        'password' => bcrypt('admin123'),
        'tempat_lahir' => 'System',
        'tanggal_lahir' => '1990-01-01',
        'jenis_kelamin' => 'L',
        'alamat' => 'System Admin',
        'email_verified_at' => now(),
    ]);
    echo "✓ Admin user created successfully!\n";
} else {
    echo "✓ Admin user already exists!\n";
}

echo "\nAdmin User Details:\n";
echo "Username: " . $adminUser->username . "\n";
echo "Email: " . $adminUser->email . "\n";
echo "Name: " . $adminUser->nama_lengkap . "\n";

echo "\n=== LOGIN CREDENTIALS ===\n";
echo "Email: admin@skilllearn.com\n";
echo "Password: admin123\n";
echo "\nYou can now login and access: http://localhost:8000/admin/courses\n";
