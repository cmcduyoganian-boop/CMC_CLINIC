<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@cmcclinic.local'],
            [
                'name' => 'Admin Nurse',
                'username' => 'admin',
                'email' => 'admin@cmcclinic.local',
                'phone' => '09123456789',
                'role' => 'clinic_nurse',
                'password' => Hash::make('Admin@123456'),
                'approval_status' => 'approved',
                'is_active' => true,
                'otp_verified' => true,
                'email_verified_at' => now(),
            ]
        );

        echo "\n✅ Admin account created!\n";
        echo "Username: admin\n";
        echo "Password: Admin@123456\n\n";
    }
}