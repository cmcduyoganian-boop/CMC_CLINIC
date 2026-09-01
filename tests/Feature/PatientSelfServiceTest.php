<?php

use App\Models\ClinicVisit;
use App\Models\Patient;
use App\Models\User;

test('patient can view and update their own profile', function () {
    $user = User::factory()->create([
        'name' => 'Patient User',
        'email' => 'patient@example.com',
        'role' => 'student',
        'approval_status' => 'approved',
        'is_active' => true,
        'otp_verified' => true,
    ]);

    $patient = Patient::create([
        'name' => 'Patient User',
        'email' => 'patient@example.com',
        'phone' => '09123456789',
        'age' => 20,
        'category' => 'student',
        'program' => 'BSCS',
        'year_section' => '2-A',
        'address' => 'Old Address',
        'status' => 'active',
    ]);

    $viewResponse = $this
        ->actingAs($user)
        ->get('/my-profile');

    $viewResponse
        ->assertOk()
        ->assertSee('Patient User')
        ->assertSee('BSCS');

    $updateResponse = $this
        ->actingAs($user)
        ->put('/my-profile', [
            'name' => 'Updated Patient User',
            'phone' => '09987654321',
            'age' => 21,
            'category' => 'student',
            'program' => 'BSIT',
            'year_section' => '3-B',
            'address' => 'New Address',
        ]);

    $updateResponse
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard');

    $patient->refresh();
    expect($patient->name)->toBe('Updated Patient User')
        ->and($patient->phone)->toBe('09987654321')
        ->and($patient->program)->toBe('BSIT')
        ->and($patient->year_section)->toBe('3-B')
        ->and($patient->address)->toBe('New Address');
});

test('patient can view their own visit records', function () {
    $user = User::factory()->create([
        'name' => 'Patient Records User',
        'email' => 'records@example.com',
        'role' => 'staff',
        'approval_status' => 'approved',
        'is_active' => true,
        'otp_verified' => true,
    ]);

    $patient = Patient::create([
        'name' => 'Patient Records User',
        'email' => 'records@example.com',
        'phone' => '09111111111',
        'age' => 25,
        'category' => 'staff',
        'program' => 'Admin',
        'year_section' => 'N/A',
        'address' => 'Test Address',
        'status' => 'active',
    ]);

    ClinicVisit::create([
        'patient_id' => $patient->id,
        'user_id' => $user->id,
        'visit_date' => '2026-08-15',
        'diagnosis' => 'Seasonal Flu',
        'management' => 'Rest and hydration',
        'temperature' => 37.5,
        'bp_systolic' => 110,
        'bp_diastolic' => 70,
        'notes' => 'Recovered well',
    ]);

    $response = $this
        ->actingAs($user)
        ->get('/my-records');

    $response
        ->assertOk()
        ->assertSee('Seasonal Flu')
        ->assertSee('My Records');
});
