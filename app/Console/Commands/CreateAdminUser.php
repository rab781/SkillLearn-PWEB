<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {email=admin@skilllearn.com} {password=admin123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for SkillLearn platform';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Check if user already exists
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            $this->warn("User with email {$email} already exists!");
            return 1;
        }

        // Create admin user
        $user = User::create([
            'username' => 'admin_' . now()->format('YmdHis'),
            'email' => $email,
            'nama_lengkap' => 'Admin SkillLearn',
            'password' => Hash::make($password),
            'tempat_lahir' => 'System',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'System Admin',
            'no_telepon' => '081234567890',
            'email_verified_at' => now(),
        ]);

        $this->info("✓ Admin user created successfully!");
        $this->line("");
        $this->line("Login Credentials:");
        $this->line("Email: {$email}");
        $this->line("Password: {$password}");
        $this->line("");
        $this->line("You can now access admin panel at: /admin/courses");
        
        return 0;
    }
}
