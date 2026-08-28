<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use App\Models\Patient;
use Livewire\Component;

class AppointmentCreateForm extends Component
{
    public $isSelfService = false;
    public $lockedPatientName = '';
    public $lockedPatientCategory = '';

    public $patientId = null;
    public $patientName = '';
    public $patientCategory = '';
    public $patientYearSection = '';
    public $showPatientDropdown = false;

    public $appointmentDate = '';
    public $appointmentTime = '';
    public $reason = '';
    public $notes = '';

    public function mount()
    {
        $this->isSelfService = auth()->user()->role !== 'clinic_nurse';

        if ($this->isSelfService) {
            $patientRecord = Patient::where('email', auth()->user()->email)->first();

            $this->lockedPatientName = $patientRecord ? $patientRecord->name : auth()->user()->name;
            $this->lockedPatientCategory = auth()->user()->role;

            $this->patientName = $this->lockedPatientName;
            $this->patientCategory = $this->lockedPatientCategory;
            $this->patientYearSection = $patientRecord ? $patientRecord->year_section : '';
        }
    }

    public function getMatchingPatientsProperty()
    {
        if ($this->isSelfService || !$this->patientName || $this->patientId) {
            return collect();
        }

        return Patient::where('name', 'like', '%' . $this->patientName . '%')
            ->limit(5)
            ->get();
    }

    public function updatedPatientName()
    {
        if ($this->isSelfService) {
            return;
        }

        $this->patientId = null;
        $this->showPatientDropdown = true;
    }

    public function selectPatient($id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return;
        }

        $this->patientId = $patient->id;
        $this->patientName = $patient->name;
        $this->patientCategory = $patient->category;
        $this->patientYearSection = $patient->year_section;
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
            'patientYearSection' => 'nullable|string',
            'appointmentDate' => 'required|date|after:today',
            'appointmentTime' => 'required|date_format:H:i',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $patient = $this->patientId
            ? Patient::find($this->patientId)
            : Patient::where('name', $validated['patientName'])->first();

        if (!$patient) {
            $patient = Patient::create([
                'name' => $validated['patientName'],
                'year_section' => $validated['patientYearSection'],
                'category' => $validated['patientCategory'],
                'status' => 'active',
            ]);
        }

        Appointment::create([
            'patient_id' => $patient->id,
            'appointment_date' => $validated['appointmentDate'],
            'appointment_time' => $validated['appointmentTime'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'status' => 'scheduled',
        ]);

        session()->flash('success', 'Appointment scheduled for ' . $patient->name . ' successfully!');

        return redirect()->route('appointments.index');
    }

    public function render()
    {
        return view('livewire.appointments.appointment-create-form');
    }
}