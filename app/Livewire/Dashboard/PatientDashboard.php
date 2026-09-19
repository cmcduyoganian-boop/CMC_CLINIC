<?php

namespace App\Livewire\Dashboard;

use App\Models\Patient;
use App\Models\ClinicVisit;
use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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

        $user = Auth::user();

        // Only allow patient roles (student, faculty, staff) to access their records
        if (!in_array($user->role, ['student', 'faculty', 'staff'], true)) {
            return;
        }

        // Use email as the primary unique identifier - it's unique in both users and patients tables
        $userEmail = strtolower(trim($user->email));

        $this->patient = Patient::whereRaw('LOWER(email) = ?', [$userEmail])->first();

        // Additional safety: verify the patient record belongs to this user by checking name similarity
        // This is a secondary check in case email matching alone isn't sufficient
        if ($this->patient) {
            $patientName = strtolower(trim($this->patient->name));
            $userName = strtolower(trim($user->name));
            
            // Names should be reasonably similar (allowing for minor variations)
            similar_text($patientName, $userName, $percent);
            if ($percent < 70) {
                // Name mismatch - possible data integrity issue, log and deny access
                \Illuminate\Support\Facades\Log::warning('Patient dashboard access denied - name mismatch', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'user_name' => $user->name,
                    'patient_id' => $this->patient->id,
                    'patient_email' => $this->patient->email,
                    'patient_name' => $this->patient->name,
                    'similarity_percent' => $percent,
                ]);
                $this->patient = null;
                return;
            }

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