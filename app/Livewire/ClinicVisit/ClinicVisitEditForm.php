<?php

namespace App\Livewire\ClinicVisit;

use App\Models\ClinicVisit;
use Livewire\Component;

class ClinicVisitEditForm extends Component
{
    public $visitId;
    public $visit;

    // ============ VISIT INFO ============
    public $visitDate;

    // ============ PATIENT INFO ============
    public $patientName;
    public $patientCategory = 'student';
    public $patientYearSection = '';
    public $patientAge = '';
    public $patientPhone = '';
    public $patientEmail = '';
    public $patientAddress = '';
    public $patientProgram = '';

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

    public function mount($visitId)
    {
        $this->visitId = $visitId;
        $this->visit = ClinicVisit::with('patient')->findOrFail($visitId);

        $this->visitDate = $this->visit->visit_date->format('Y-m-d');
        $this->temperature = $this->visit->temperature;
        $this->pulseRate = $this->visit->pulse_rate;
        $this->respiratoryRate = $this->visit->respiratory_rate;
        $this->bpSystolic = $this->visit->bp_systolic;
        $this->bpDiastolic = $this->visit->bp_diastolic;
        $this->height = $this->visit->height;
        $this->weight = $this->visit->weight;
        $this->spo2 = $this->visit->spo2;
        $this->complaints = $this->visit->complaints;
        $this->management = $this->visit->management;
        $this->diagnosis = $this->visit->diagnosis;
        $this->notes = $this->visit->notes;

        $patient = $this->visit->patient;
        $this->patientName = $patient->name ?? '';
        $this->patientCategory = $patient->category ?? 'student';
        $this->patientYearSection = $patient->year_section ?? '';
        $this->patientAge = $patient->age ?? '';
        $this->patientPhone = $patient->phone ?? '';
        $this->patientEmail = $patient->email ?? '';
        $this->patientAddress = $patient->address ?? '';
        $this->patientProgram = $patient->program ?? '';
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

    public function save()
    {
        $validated = $this->validate([
            'visitDate' => 'required|date',
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
            'patientName' => 'required|string|max:255',
            'patientCategory' => 'required|in:student,faculty,staff',
            'patientYearSection' => 'nullable|string|max:50',
            'patientAge' => 'nullable|integer|min:0',
            'patientPhone' => 'nullable|string|max:20',
            'patientEmail' => 'nullable|email|max:255',
            'patientAddress' => 'nullable|string|max:500',
            'patientProgram' => 'nullable|string|max:100',
        ]);

        $this->visit->update([
            'visit_date' => $validated['visitDate'],
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
        ]);

        if ($this->visit->patient) {
            $this->visit->patient->update([
                'name' => $validated['patientName'],
                'category' => $validated['patientCategory'],
                'year_section' => $validated['patientYearSection'],
                'age' => $validated['patientAge'],
                'phone' => $validated['patientPhone'],
                'email' => $validated['patientEmail'],
                'address' => $validated['patientAddress'],
                'program' => $validated['patientProgram'],
            ]);
        }

        session()->flash('success', 'Clinic visit and patient information updated successfully!');

        return redirect()->route('clinic-visit.index');
    }

    public function render()
    {
        return view('livewire.clinic-visit.clinic-visit-edit-form');
    }
}