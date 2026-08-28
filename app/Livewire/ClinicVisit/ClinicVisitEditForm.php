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

        session()->flash('success', 'Clinic visit updated successfully!');

        return redirect()->route('clinic-visit.index');
    }

    public function render()
    {
        return view('livewire.clinic-visit.clinic-visit-edit-form');
    }
}