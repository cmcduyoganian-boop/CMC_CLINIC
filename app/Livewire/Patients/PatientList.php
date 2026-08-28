<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PatientList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';

    #[On('global-search')]
    public function updateGlobalSearch($term)
    {
        $this->search = $term;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function deletePatient($id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return;
        }

        $name = $patient->name;
        $patient->delete();

        session()->flash('success', 'Patient ' . $name . ' and all their records have been deleted.');
        $this->resetPage();
    }

    public function render()
    {
        $query = Patient::withCount('clinicVisits')
            ->with(['clinicVisits' => function ($q) {
                $q->orderBy('visit_date', 'desc');
            }]);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->category) {
            $query->where('category', $this->category);
        }

        $patients = $query->orderBy('name')->paginate(10);

        return view('livewire.patients.patient-list', [
            'patients' => $patients,
            'totalPatients' => Patient::count(),
            'studentCount' => Patient::where('category', 'student')->count(),
            'facultyCount' => Patient::where('category', 'faculty')->count(),
            'staffCount' => Patient::where('category', 'staff')->count(),
        ]);
    }
}