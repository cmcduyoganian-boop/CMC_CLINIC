<?php

namespace App\Livewire\Forms;

use App\Models\StudentHealthRecord;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentInfo extends Component
{
    public ?StudentHealthRecord $record = null;
    public bool $isEdit = false;

    // Student Code
    public string $studentCode = '';

    // Personal Information
    public string $last_name = '';
    public string $first_name = '';
    public string $middle_name = '';
    public string $suffix = '';
    public string $maiden_name = '';
    public string $sex = '';
    public string $birthday = '';
    public string $birthplace = '';
    public string $blood_type = '';
    public string $mother_name = '';
    public string $father_name = '';
    public string $civil_status = '';
    public string $residential_address = '';
    public string $height = '';
    public string $weight = '';
    public string $course = '';
    public string $year_section = '';
    public string $contact_number = '';
    public string $spouse_name = '';

    // Past Medical History
    public array $pastMedicalHistory = [
        'allergy' => false,
        'allergy_specify' => '',
        'asthma' => false,
        'cancer' => false,
        'cerebrovascular_disease' => false,
        'diabetes' => false,
        'maintenance' => '',
        'epilepsy' => false,
        'emphysema' => false,
        'hepatitis' => false,
        'hepatitis_type' => '',
        'hypertension' => false,
        'hyperlipidemia' => false,
        'peptic_ulcer' => false,
        'pneumonia' => false,
        'thyroid_disease' => false,
        'pulmonary_tb' => false,
        'urinary_tract_infection' => false,
        'mental_illness' => false,
        'others_medical' => false,
        'others_medical_specify' => '',
        'none_medical' => false,
    ];

    // Past Surgical History
    public array $pastSurgicalHistory = [
        ['operation' => '', 'date' => ''],
        ['operation' => '', 'date' => ''],
        ['operation' => '', 'date' => ''],
    ];

    // Family History
    public array $familyHistory = [
        'allergy' => false,
        'allergy_specify' => '',
        'asthma' => false,
        'cancer' => false,
        'cerebrovascular_disease' => false,
        'diabetes' => false,
        'maintenance' => '',
        'epilepsy' => false,
        'emphysema' => false,
        'hepatitis' => false,
        'hepatitis_type' => '',
        'hypertension' => false,
        'hyperlipidemia' => false,
        'peptic_ulcer' => false,
        'pneumonia' => false,
        'thyroid_disease' => false,
        'pulmonary_tb' => false,
        'urinary_tract_infection' => false,
        'mental_illness' => false,
        'others' => false,
        'others_specify' => '',
        'none' => false,
    ];

    public string $signature_name = '';
    public string $signature_date = '';
    public string $healthcare_provider_name = '';

    public function mount(?string $studentCode = null)
    {
        $user = Auth::user();

        if ($user && $user->role === 'student') {
            $this->studentCode = $user->student_code ?? '';
            $this->first_name = $user->name ?? '';
        }

        if ($studentCode) {
            $this->studentCode = $studentCode;
        }

        $existing = StudentHealthRecord::where('student_code', $this->studentCode)
            ->where('user_id', $user?->id)
            ->latest()
            ->first();

        if ($existing) {
            $this->isEdit = true;
            $this->record = $existing;
            $this->fillFromRecord($existing);
        }

        if (!$this->signature_date) {
            $this->signature_date = now()->format('Y-m-d');
        }
    }

    protected function fillFromRecord(StudentHealthRecord $record): void
    {
        $this->last_name = $record->last_name;
        $this->first_name = $record->first_name;
        $this->middle_name = $record->middle_name ?? '';
        $this->suffix = $record->suffix ?? '';
        $this->maiden_name = $record->maiden_name ?? '';
        $this->sex = $record->sex;
        $this->birthday = $record->birthday ?? '';
        $this->birthplace = $record->birthplace ?? '';
        $this->blood_type = $record->blood_type ?? '';
        $this->mother_name = $record->mother_name ?? '';
        $this->father_name = $record->father_name ?? '';
        $this->civil_status = $record->civil_status ?? '';
        $this->residential_address = $record->residential_address ?? '';
        $this->height = $record->height ?? '';
        $this->weight = $record->weight ?? '';
        $this->course = $record->course ?? '';
        $this->year_section = $record->year_section ?? '';
        $this->contact_number = $record->contact_number ?? '';
        $this->spouse_name = $record->spouse_name ?? '';
        $this->pastMedicalHistory = $record->past_medical_history ?? $this->pastMedicalHistory;
        $this->pastSurgicalHistory = $record->past_surgical_history ?? $this->pastSurgicalHistory;
        $this->familyHistory = $record->family_history ?? $this->familyHistory;
        $this->signature_name = $record->signature_name ?? '';
        $this->signature_date = $record->signature_date ?? '';
        $this->healthcare_provider_name = $record->healthcare_provider_name ?? '';
    }

    public function submit()
    {
        $this->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'sex' => 'required|string|max:20',
            'birthday' => 'nullable|string|max:20',
            'course' => 'nullable|string|max:255',
            'year_section' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        $data = [
            'user_id' => $user?->id,
            'student_code' => $this->studentCode,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'suffix' => $this->suffix,
            'maiden_name' => $this->maiden_name,
            'sex' => $this->sex,
            'birthday' => $this->birthday,
            'birthplace' => $this->birthplace,
            'blood_type' => $this->blood_type,
            'mother_name' => $this->mother_name,
            'father_name' => $this->father_name,
            'civil_status' => $this->civil_status,
            'residential_address' => $this->residential_address,
            'height' => $this->height,
            'weight' => $this->weight,
            'course' => $this->course,
            'year_section' => $this->year_section,
            'contact_number' => $this->contact_number,
            'spouse_name' => $this->spouse_name,
            'past_medical_history' => $this->pastMedicalHistory,
            'past_surgical_history' => $this->pastSurgicalHistory,
            'family_history' => $this->familyHistory,
            'signature_name' => $this->signature_name,
            'signature_date' => $this->signature_date,
            'healthcare_provider_name' => $this->healthcare_provider_name,
        ];

        if ($this->isEdit && $this->record) {
            $this->record->update($data);
            $message = 'Student health record updated successfully.';
        } else {
            $this->record = StudentHealthRecord::create($data);
            $this->isEdit = true;
            $message = 'Student health record saved successfully.';
        }

        session()->flash('message', $message);
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function exportPdf()
    {
        $this->submit();

        $pdf = Pdf::loadView('pdf.student-health-record', [
            'record' => $this->record,
            'data' => [
                'studentCode' => $this->studentCode,
                'last_name' => $this->last_name,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'suffix' => $this->suffix,
                'maiden_name' => $this->maiden_name,
                'sex' => $this->sex,
                'birthday' => $this->birthday,
                'birthplace' => $this->birthplace,
                'blood_type' => $this->blood_type,
                'mother_name' => $this->mother_name,
                'father_name' => $this->father_name,
                'civil_status' => $this->civil_status,
                'residential_address' => $this->residential_address,
                'height' => $this->height,
                'weight' => $this->weight,
                'course' => $this->course,
                'year_section' => $this->year_section,
                'contact_number' => $this->contact_number,
                'spouse_name' => $this->spouse_name,
                'pastMedicalHistory' => $this->pastMedicalHistory,
                'pastSurgicalHistory' => $this->pastSurgicalHistory,
                'familyHistory' => $this->familyHistory,
                'signature_name' => $this->signature_name,
                'signature_date' => $this->signature_date,
                'healthcare_provider_name' => $this->healthcare_provider_name,
            ],
        ]);

        return $pdf->download('student-health-record-' . $this->studentCode . '-' . now()->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        $user = Auth::user();
        $isStudent = $user && $user->role === 'student';
        $isClinicStaff = $user && in_array($user->role, ['clinic_nurse', 'clinic_staff'], true);

        return view('livewire.forms.student-info', [
            'isStudent' => $isStudent,
            'isClinicStaff' => $isClinicStaff,
        ]);
    }
}
