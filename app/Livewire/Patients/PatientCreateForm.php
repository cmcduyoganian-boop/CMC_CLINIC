<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Livewire\Component;

class PatientCreateForm extends Component
{
    public $name = '';
    public $category = '';
    public $program = '';
    public $yearSection = '';
    public $age = '';
    public $email = '';
    public $phone = '';
    public $address = '';

    public $programs = ['BSED', 'BEED', 'BSHM', 'BSBA', 'BSOA', 'BSIS', 'BSCS', 'BSAS', 'BSCRIM', 'Other'];

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:student,faculty,staff',
            'program' => 'nullable|string|max:255',
            'yearSection' => 'nullable|string|max:255',
            'age' => 'nullable|integer',
            'email' => 'nullable|email|unique:patients,email',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $patient = Patient::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'program' => $validated['program'],
            'year_section' => $validated['yearSection'],
            'age' => $validated['age'] ?: null,
            'email' => $validated['email'] ?: null,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => 'active',
        ]);

        session()->flash('success', 'Patient ' . $patient->name . ' added successfully!');

        return redirect()->route('patients.index');
    }

    public function render()
    {
        return view('livewire.patients.patient-create-form');
    }
}