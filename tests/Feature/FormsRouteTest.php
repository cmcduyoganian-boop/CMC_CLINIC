<?php

use App\Livewire\Forms\StudentInfo;
use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

test('form pages routes are registered', function () {
    expect(Route::has('forms.consent'))->toBeTrue()
        ->and(Route::has('forms.student-info'))->toBeTrue();
});

test('consent form shows a visible client name input for the signature line', function () {
    $user = User::factory()->create([
        'role' => 'clinic_nurse',
        'approval_status' => 'approved',
        'is_active' => true,
    ]);

    $this->actingAs($user);

    $this->get(route('forms.consent'))
        ->assertSee('name="client_signature"')
        ->assertSee('placeholder="Signature over Printed Name"')
        ->assertDontSee('type="hidden" name="client_signature"');
});

test('livewire student health form saves a submission that appears in the records list', function () {
    $user = User::factory()->create([
        'role' => 'clinic_nurse',
        'approval_status' => 'approved',
        'is_active' => true,
    ]);

    $this->actingAs($user);

    Livewire::test(StudentInfo::class)
        ->set('studentCode', '2026-001')
        ->set('last_name', 'Dela Cruz')
        ->set('first_name', 'Maria')
        ->set('middle_name', 'Santos')
        ->set('sex', 'Female')
        ->set('birthday', '2005-01-15')
        ->set('course', 'BSIT')
        ->set('year_section', '2-A')
        ->set('contact_number', '09171234567')
        ->set('civil_status', 'Single')
        ->call('submit');

    $submission = FormSubmission::where('user_id', $user->id)
        ->where('form_type', 'student_medical_history')
        ->latest('submitted_at')
        ->first();

    expect($submission)->not->toBeNull()
        ->and($submission->data['last_name'])->toBe('Dela Cruz')
        ->and($submission->data['first_name'])->toBe('Maria')
        ->and($submission->data['course'])->toBe('BSIT')
        ->and($submission->data['year_section'])->toBe('2-A');

    $this->get(route('forms.index'))
        ->assertSee('Dela Cruz')
        ->assertSee('Maria')
        ->assertSee('BSIT');
});

test('client consent can be saved without a required signature date field', function () {
    $user = User::factory()->create([
        'role' => 'clinic_nurse',
        'approval_status' => 'approved',
        'is_active' => true,
    ]);

    $this->actingAs($user);

    $response = $this->post(route('forms.consent.store'), [
        'full_name' => 'Maria Dela Cruz',
        'date_of_birth' => '2005-02-10',
        'address' => 'Carmen, Bohol',
        'phone_number' => '09171234567',
        'emergency_contact_name' => 'Juan Dela Cruz',
        'emergency_contact_number' => '09187654321',
        'client_signature' => 'Maria Dela Cruz',
        'guardian_signature' => '',
    ]);

    $response->assertRedirect(route('forms.consent'));
});

test('student medical submissions appear in the saved records list even without student code or contact number', function () {
    $user = User::factory()->create([
        'role' => 'clinic_nurse',
        'approval_status' => 'approved',
        'is_active' => true,
    ]);

    $this->actingAs($user);

    FormSubmission::create([
        'user_id' => $user->id,
        'form_type' => 'student_medical_history',
        'data' => [
            'last_name' => 'Dela Cruz',
            'first_name' => 'Maria',
            'middle_name' => '',
            'sex' => 'Female',
            'course' => 'BSIT',
            'year_section' => '2-A',
            'student_code' => null,
            'contact_number' => null,
        ],
        'submitted_at' => now(),
    ]);

    $this->get(route('forms.index'))
        ->assertSee('Dela Cruz')
        ->assertSee('Maria')
        ->assertSee('BSIT');
});

test('clinic visit form saves as a single latest record per user', function () {
    $user = User::factory()->create([
        'role' => 'clinic_nurse',
        'approval_status' => 'approved',
        'is_active' => true,
    ]);

    $this->actingAs($user);

    $this->post(route('forms.clinic-visit.store'), [
        'visit_date' => '2026-09-21',
        'age' => 18,
        'temperature' => 36.5,
        'pulse_rate' => 72,
        'respiratory_rate' => 18,
        'blood_pressure' => '110/70',
        'height' => 160,
        'weight' => 55,
        'bmi' => 21.5,
        'spo2' => 98,
        'complaints' => 'Dizziness',
        'management' => 'Rest',
        'diagnosis' => 'Tired',
        'signature' => $user->name,
    ])->assertRedirect(route('forms.clinic-visit'));

    $this->post(route('forms.clinic-visit.store'), [
        'visit_date' => '2026-09-22',
        'age' => 19,
        'temperature' => 37.0,
        'pulse_rate' => 75,
        'respiratory_rate' => 20,
        'blood_pressure' => '112/72',
        'height' => 161,
        'weight' => 56,
        'bmi' => 21.6,
        'spo2' => 99,
        'complaints' => 'Headache',
        'management' => 'Hydration',
        'diagnosis' => 'Dehydration',
        'signature' => $user->name,
    ]);

    $records = FormSubmission::where('user_id', $user->id)
        ->where('form_type', 'clinic_visit_log')
        ->get();

    expect($records)->toHaveCount(1)
        ->and($records->first()->data['complaints'])->toBe('Headache')
        ->and($records->first()->data['diagnosis'])->toBe('Dehydration');
});
