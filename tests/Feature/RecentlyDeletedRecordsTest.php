<?php

use App\Models\Appointment;
use App\Models\ClinicVisit;
use App\Models\Patient;
use App\Models\User;

test('deleted patient records appear in recently deleted and can be restored', function () {
    $user = User::factory()->create([
        'name' => 'Clinic Nurse',
        'email' => 'nurse@example.com',
        'role' => 'clinic_nurse',
        'approval_status' => 'approved',
        'is_active' => true,
        'otp_verified' => true,
    ]);

    $patient = Patient::create([
        'name' => 'Deleted Patient',
        'email' => 'deleted.patient@example.com',
        'phone' => '09123456789',
        'category' => 'student',
        'program' => 'BSCS',
        'year_section' => '2-A',
        'status' => 'active',
    ]);

    $visit = ClinicVisit::create([
        'patient_id' => $patient->id,
        'user_id' => $user->id,
        'visit_date' => '2026-09-19',
        'complaints' => 'Headache',
        'diagnosis' => 'Migraine',
    ]);

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'appointment_date' => '2026-09-21',
        'appointment_time' => '09:00:00',
        'reason' => 'Check-up',
        'status' => 'scheduled',
    ]);

    $patient->delete();

    expect(Patient::count())->toBe(0)
        ->and(Patient::withTrashed()->count())->toBe(1)
        ->and(ClinicVisit::count())->toBe(0)
        ->and(ClinicVisit::withTrashed()->count())->toBe(1)
        ->and(Appointment::count())->toBe(0)
        ->and(Appointment::withTrashed()->count())->toBe(1);

    $this->actingAs($user)
        ->get('/recently-deleted')
        ->assertOk()
        ->assertSee('Recently Deleted')
        ->assertSee('Deleted Patient');

    $this->actingAs($user)
        ->post('/recently-deleted/patient/' . $patient->id . '/restore')
        ->assertRedirect('/recently-deleted');

    expect(Patient::withTrashed()->find($patient->id)->trashed())->toBeFalse()
        ->and(ClinicVisit::withTrashed()->find($visit->id)->trashed())->toBeFalse()
        ->and(Appointment::withTrashed()->find($appointment->id)->trashed())->toBeFalse();
});

test('deleted records can be permanently removed or cleared in bulk', function () {
    $user = User::factory()->create([
        'name' => 'Clinic Nurse',
        'email' => 'nurse2@example.com',
        'role' => 'clinic_nurse',
        'approval_status' => 'approved',
        'is_active' => true,
        'otp_verified' => true,
    ]);

    $patient = Patient::create([
        'name' => 'Permanent Delete Patient',
        'email' => 'perm.delete@example.com',
        'phone' => '09123456789',
        'category' => 'faculty',
        'status' => 'active',
    ]);

    $visit = ClinicVisit::create([
        'patient_id' => $patient->id,
        'user_id' => $user->id,
        'visit_date' => '2026-09-20',
        'diagnosis' => 'Follow up',
    ]);

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'appointment_date' => '2026-09-22',
        'appointment_time' => '10:00:00',
        'reason' => 'Review',
        'status' => 'scheduled',
    ]);

    $patient->delete();

    $this->actingAs($user)
        ->post('/recently-deleted/patient/' . $patient->id . '/force-delete')
        ->assertRedirect('/recently-deleted');

    expect(Patient::withTrashed()->find($patient->id))->toBeNull()
        ->and(ClinicVisit::withTrashed()->find($visit->id))->toBeNull()
        ->and(Appointment::withTrashed()->find($appointment->id))->toBeNull();

    $anotherPatient = Patient::create([
        'name' => 'Bulk Clear Patient',
        'email' => 'bulk.clear@example.com',
        'phone' => '09999999999',
        'category' => 'staff',
        'status' => 'active',
    ]);
    $anotherPatient->delete();

    $this->actingAs($user)
        ->post('/recently-deleted/clear-all')
        ->assertRedirect('/recently-deleted');

    expect(Patient::withTrashed()->count())->toBe(0)
        ->and(ClinicVisit::withTrashed()->count())->toBe(0)
        ->and(Appointment::withTrashed()->count())->toBe(0);
});
