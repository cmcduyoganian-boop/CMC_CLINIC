<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing admin if any
        User::where('username', 'admin')->delete();

        // Create fresh admin user
        User::create([
            'name' => 'Admin Nurse',
            'username' => 'admin',
            'email' => 'admin@cmc.clinic',
            'phone' => '09123456789',
            'role' => 'clinic_nurse',
            'approval_status' => 'approved',  // IMPORTANT: Must be approved!
            'is_active' => true,
            'password' => Hash::make('Admin@123456'),  // HASHED PASSWORD
            'email_verified_at' => now(),
        ]);

        echo "✓ Admin user created successfully!\n";
        echo "Username: admin\n";
        echo "Password: Admin@123456\n";
    }
}