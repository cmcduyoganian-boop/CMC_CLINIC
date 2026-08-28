<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        Patient::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'phone' => '09123456789',
            'year_section' => 'BSCS-2A',
            'age' => 20,
            'blood_type' => 'O+',
            'address' => 'Carmen, Bohol',
            'mother_name' => 'Maria Dela Cruz',
            'father_name' => 'Pedro Dela Cruz',
            'status' => 'active',
        ]);

        Patient::create([
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'phone' => '09198765432',
            'year_section' => 'BSED-1B',
            'age' => 19,
            'blood_type' => 'A+',
            'address' => 'Carmen, Bohol',
            'mother_name' => 'Rosa Santos',
            'father_name' => 'Juan Santos',
            'status' => 'active',
        ]);

        Patient::create([
            'name' => 'Pedro Reyes',
            'email' => 'pedro@example.com',
            'phone' => '09156789012',
            'year_section' => 'BSHM-3A',
            'age' => 21,
            'blood_type' => 'B+',
            'address' => 'Carmen, Bohol',
            'mother_name' => 'Ana Reyes',
            'father_name' => 'Carlos Reyes',
            'status' => 'active',
        ]);

        Patient::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@example.com',
            'phone' => '09134567890',
            'year_section' => 'BSAS-2C',
            'age' => 20,
            'blood_type' => 'AB+',
            'address' => 'Carmen, Bohol',
            'mother_name' => 'Jane Johnson',
            'father_name' => 'Robert Johnson',
            'status' => 'active',
        ]);
    }
}