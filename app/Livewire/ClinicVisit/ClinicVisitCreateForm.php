<?php

namespace App\Livewire\ClinicVisit;

use App\Models\ClinicVisit;
use App\Models\Patient;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClinicVisitCreateForm extends Component
{
    // ============ PATIENT INFO ============
    public ?int $patientId = null;
    public $patientName = '';
    public $patientCategory = '';
    public $patientProgram = '';
    public $patientYearSection = '';
    public $patientAge = '';
    public $patientPhone = '';
    public $patientEmail = '';
    public $showPatientDropdown = false;

    // ============ VISIT INFO ============
    public ?string $visitDate = null;
    public $visitType = 'walk_in';
    public $address = '';
    public $sex = '';

    // ============ VITAL SIGNS ============
    public $temperature = '';
    public $pulseRate = '';
    public $respiratoryRate = '';
    public $bpSystolic = '';
    public $bpDiastolic = '';
    public $height = '';
    public $weight = '';
    public $spo2 = '';

    // ============ CLINICAL INFO ============
    public $complaints = '';
    public $management = '';
    public $diagnosis = '';
    public $notes = '';
    public $services = [];

    public $serviceOptions = [
        'Vital Signs',
        'Health Education',
        'Referral',
        'First Aid',
        'Counseling',
        'Medicine Dispensing',
        'Wound Dressing',
        'Other',
    ];

    public $programs = ['BSED', 'BEED', 'BSHM', 'BSBA', 'BSOA', 'BSIS', 'BSCS', 'BSAS', 'BSCRIM', 'Other'];

    public function mount()
    {
        $this->visitDate = now()->format('Y-m-d');

        // Pre-fill patient if arriving via "New Visit" link from the Patients page
        if (request()->has('patient')) {
            $patient = Patient::find(request()->query('patient'));
            if ($patient) {
                $this->selectPatient($patient->id);
            }
        }
    }

    public function getMatchingPatientsProperty()
    {
        if (!$this->patientName || $this->patientId) {
            return collect();
        }

        return Patient::where('name', 'like', '%' . $this->patientName . '%')
            ->limit(5)
            ->get();
    }

    public function getBmiProperty()
    {
        if (!$this->height || !$this->weight || (float) $this->height <= 0) {
            return null;
        }

        $heightInMeters = (float) $this->height / 100;
        $bmi = (float) $this->weight / ($heightInMeters * $heightInMeters);

        return round($bmi, 2);
    }

    public function getBmiCategoryProperty()
    {
        $bmi = $this->bmi;

        if ($bmi === null) {
            return null;
        }

        return match (true) {
            $bmi < 18.5 => ['label' => 'Underweight', 'class' => 'bmi-under'],
            $bmi < 25 => ['label' => 'Normal', 'class' => 'bmi-normal'],
            $bmi < 30 => ['label' => 'Overweight', 'class' => 'bmi-over'],
            default => ['label' => 'Obese', 'class' => 'bmi-obese'],
        };
    }

    public function updatedPatientName()
    {
        $this->patientId = null;
        $this->showPatientDropdown = true;
    }

    public function updatedPatientCategory()
    {
        $this->patientProgram = '';

        if (in_array($this->patientCategory, ['faculty', 'staff'], true)) {
            $this->patientYearSection = '';
        }
    }

    public function selectPatient(int $id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return;
        }

        $this->patientId = $patient->id;
        $this->patientName = $patient->name;
        $this->patientCategory = $patient->category;
        $this->patientProgram = $patient->program;
        $this->patientYearSection = in_array($patient->category, ['faculty', 'staff'], true)
            ? ''
            : $patient->year_section;
        $this->patientAge = $patient->age ?? '';
        $this->patientPhone = $patient->phone ?? '';
        $this->patientEmail = $patient->email ?? '';
        $this->address = trim((string) ($patient->address ?? ''));
        $this->showPatientDropdown = false;
    }

    public function hideDropdown()
    {
        $this->showPatientDropdown = false;
    }

    public function save()
    {
        $validated = $this->validate([
            'patientName' => 'required|string|max:255',
            'patientCategory' => 'required|in:student,faculty,staff',
            'patientProgram' => 'nullable|string|max:255',
            'patientYearSection' => 'nullable|string|max:255',
            'patientAge' => 'nullable|integer|min:0|max:150',
            'patientPhone' => 'nullable|string|max:30',
            'patientEmail' => 'required|email|max:255',
            'visitDate' => 'required|date',
            'visitType' => 'required|in:walk_in,appointment,follow_up',
            'address' => 'required|string|max:500',
            'sex' => 'required|in:male,female',
            'temperature' => 'nullable|numeric',
            'pulseRate' => 'nullable|numeric',
            'respiratoryRate' => 'nullable|numeric',
            'bpSystolic' => 'nullable|numeric',
            'bpDiastolic' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'spo2' => 'nullable|numeric',
            'complaints' => 'nullable|string',
            'management' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*' => 'string|max:255',
        ]);

        // Find or create patient (same logic as the original controller)
        $patient = $this->patientId
            ? Patient::find($this->patientId)
            : Patient::where('name', $validated['patientName'])->first();

        $validated['address'] = trim((string) ($validated['address'] ?: ($patient->address ?? '')));

        if (!$patient) {
            $patient = Patient::create([
                'name' => $validated['patientName'],
                'category' => $validated['patientCategory'],
                'program' => $validated['patientProgram'],
                'year_section' => $validated['patientYearSection'],
                'age' => $validated['patientAge'],
                'phone' => $validated['patientPhone'],
                'email' => $validated['patientEmail'] ?: null,
                'address' => $validated['address'],
                'status' => 'active',
            ]);
        } else {
            $patient->update([
                'age' => $validated['patientAge'],
                'phone' => $validated['patientPhone'],
                'email' => $validated['patientEmail'] ?: null,
                'address' => $validated['address'],
            ]);
        }

        ClinicVisit::create([
            'patient_id' => $patient->id,
            'user_id' => Auth::id(),
            'visit_date' => $validated['visitDate'],
            'visit_type' => $validated['visitType'],
            'address' => $validated['address'],
            'sex' => $validated['sex'],
            'temperature' => $validated['temperature'] ?: null,
            'pulse_rate' => $validated['pulseRate'] ?: null,
            'respiratory_rate' => $validated['respiratoryRate'] ?: null,
            'bp_systolic' => $validated['bpSystolic'] ?: null,
            'bp_diastolic' => $validated['bpDiastolic'] ?: null,
            'height' => $validated['height'] ?: null,
            'weight' => $validated['weight'] ?: null,
            'bmi' => $this->bmi,
            'spo2' => $validated['spo2'] ?: null,
            'complaints' => $validated['complaints'],
            'management' => $validated['management'],
            'diagnosis' => $validated['diagnosis'],
            'notes' => $validated['notes'],
            'services' => $validated['services'] ?: null,
        ]);

        Log::info('Clinic visit created', [
            'visit_id' => $patient->id,
            'address' => $validated['address'],
            'all_data' => $validated
        ]);

        session()->flash('success', 'Clinic visit recorded successfully! Patient: ' . $patient->name);

        return redirect()->route('clinic-visit.index');
    }

    public function render()
    {
        return view('livewire.clinic-visit.clinic-visit-create-form');
    }
}