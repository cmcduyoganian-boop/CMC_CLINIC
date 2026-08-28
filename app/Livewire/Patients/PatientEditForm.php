<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Livewire\Component;

class PatientEditForm extends Component
{
    public $patientId;
    public $patient;

    public $name = '';
    public $category = '';
    public $program = '';
    public $yearSection = '';
    public $age = '';
    public $email = '';
    public $phone = '';
    public $address = '';

    public $programs = ['BSED', 'BEED', 'BSHM', 'BSBA', 'BSOA', 'BSIS', 'BSCS', 'BSAS', 'BSCRIM', 'Other'];

    public function mount($patientId)
    {
        $this->patientId = $patientId;
        $this->patient = Patient::findOrFail($patientId);

        $this->name = $this->patient->name;
        $this->category = $this->patient->category;
        $this->program = $this->patient->program;
        $this->yearSection = $this->patient->year_section;
        $this->age = $this->patient->age;
        $this->email = $this->patient->email;
        $this->phone = $this->patient->phone;
        $this->address = $this->patient->address;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:student,faculty,staff',
            'program' => 'nullable|string|max:255',
            'yearSection' => 'nullable|string|max:255',
            'age' => 'nullable|integer',
            'email' => 'nullable|email|unique:patients,email,' . $this->patientId,
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $this->patient->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'program' => $validated['program'],
            'year_section' => $validated['yearSection'],
            'age' => $validated['age'] ?: null,
            'email' => $validated['email'] ?: null,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        session()->flash('success', 'Patient ' . $this->patient->name . ' updated successfully!');

        return redirect()->route('patients.index');
    }

    public function render()
    {
        return view('livewire.patients.patient-edit-form');
    }
}