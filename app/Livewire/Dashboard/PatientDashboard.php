<?php

namespace App\Livewire\Dashboard;

use App\Models\Patient;
use App\Models\ClinicVisit;
use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Str;

class PatientDashboard extends Component
{
    public $patient;
    public $visits = [];
    public $appointments = [];
    public $lastVisit;
    public $totalVisits = 0;
    public $upcomingAppointments = 0;

    public function mount()
    {
        $this->loadPatientData();
    }

    public function loadPatientData()
    {
        $this->patient = null;
        $this->visits = [];
        $this->appointments = [];
        $this->lastVisit = null;
        $this->totalVisits = 0;
        $this->upcomingAppointments = 0;

        $user = auth()->user();
        $accountName = Str::lower(Str::squish($user->name));
        $accountEmail = Str::lower(trim($user->email));

        // A patient account can access records only when both identity fields match.
        $this->patient = Patient::whereRaw('LOWER(email) = ?', [$accountEmail])
            ->get()
            ->first(function (Patient $patient) use ($accountName) {
                return Str::lower(Str::squish($patient->name)) === $accountName;
            });

        if ($this->patient) {
            // Get clinic visits
            $this->visits = ClinicVisit::where('patient_id', $this->patient->id)
                ->orderBy('visit_date', 'desc')
                ->limit(10)
                ->get();

            // Get total visits
            $this->totalVisits = ClinicVisit::where('patient_id', $this->patient->id)->count();

            // Get last visit
            $this->lastVisit = ClinicVisit::where('patient_id', $this->patient->id)
                ->orderBy('visit_date', 'desc')
                ->first();

            // Get appointments
            $this->appointments = Appointment::where('patient_id', $this->patient->id)
                ->where('appointment_date', '>=', today())
                ->orderBy('appointment_date', 'asc')
                ->limit(5)
                ->get();

            // Count upcoming appointments
            $this->upcomingAppointments = Appointment::where('patient_id', $this->patient->id)
                ->where('status', 'scheduled')
                ->where('appointment_date', '>=', today())
                ->count();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.patient-dashboard');
    }
}