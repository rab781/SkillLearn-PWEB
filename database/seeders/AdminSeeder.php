<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Admin Skillearn',
            'no_telepon' => '081234567890',
            'username' => 'admin',
            'email' => 'admin@skillearn.com',
            'password' => Hash::make('password123'),
            'role' => 'AD'
        ]);

        User::create([
            'nama_lengkap' => 'Customer Test',
            'no_telepon' => '081234567891',
            'username' => 'customer',
            'email' => 'customer@skillearn.com',
            'password' => Hash::make('password123'),
            'role' => 'CU'
        ]);
    }
}
