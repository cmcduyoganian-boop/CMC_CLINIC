<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\ClinicVisit;
use App\Models\Appointment;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function patients()
    {
        $patients = Patient::with('clinicVisits')
            ->withCount('clinicVisits')
            ->orderBy('name')
            ->get();

        return view('reports.patients', [
            'totalPatients' => Patient::count(),
            'students' => Patient::where('category', 'student')->count(),
            'faculty' => Patient::where('category', 'faculty')->count(),
            'staff' => Patient::where('category', 'staff')->count(),
            'activePatients' => Patient::where('status', 'active')->count(),
            'inactivePatients' => Patient::where('status', 'inactive')->count(),
            'patients' => $patients,
        ]);
    }

    public function clinicVisits()
    {
        $last30Days = collect(range(29, 0))->map(function ($days) {
            $date = now()->subDays($days);
            return [
                'date' => $date->format('M d'),
                'count' => ClinicVisit::whereDate('visit_date', $date)->count(),
            ];
        });

        return view('reports.clinic-visits', [
            'totalVisits' => ClinicVisit::count(),
            'todayVisits' => ClinicVisit::whereDate('visit_date', today())->count(),
            'monthVisits' => ClinicVisit::whereBetween('visit_date', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])->count(),
            'uniquePatients' => ClinicVisit::distinct('patient_id')->count('patient_id'),
            'last30Days' => $last30Days,
            'recentVisits' => ClinicVisit::with('patient')
                ->orderBy('visit_date', 'desc')
                ->limit(20)
                ->get(),
        ]);
    }

    public function diagnosis()
    {
        $topDiagnoses = ClinicVisit::select('diagnosis')
            ->whereNotNull('diagnosis')
            ->where('diagnosis', '!=', '')
            ->groupBy('diagnosis')
            ->selectRaw('diagnosis, COUNT(*) as count')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->get();

        return view('reports.diagnosis', [
            'topDiagnoses' => $topDiagnoses,
            'totalUniqueDiagnoses' => ClinicVisit::whereNotNull('diagnosis')
                ->distinct('diagnosis')
                ->count('diagnosis'),
        ]);
    }

    public function medicines()
    {
        $medicines = Medicine::where('status', 'active')
            ->orderBy('quantity')
            ->get();

        return view('reports.medicines', [
            'totalMedicines' => Medicine::count(),
            'lowStock' => Medicine::whereRaw('quantity <= minimum_stock')->count(),
            'outOfStock' => Medicine::where('quantity', '<=', 0)->count(),
            'medicines' => $medicines,
        ]);
    }

    public function appointments()
    {
        return view('reports.appointments', [
            'totalAppointments' => Appointment::count(),
            'scheduled' => Appointment::where('status', 'scheduled')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'noShow' => Appointment::where('status', 'no-show')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
            'appointments' => Appointment::with('patient')
                ->orderBy('appointment_date', 'desc')
                ->limit(20)
                ->get(),
        ]);
    }

    public function vitalSigns()
    {
        $abnormal = ClinicVisit::where(function ($query) {
            $query->where('temperature', '>', 38)
                  ->orWhere('temperature', '<', 36)
                  ->orWhere('spo2', '<', 95);
        })->with('patient')
        ->orderBy('visit_date', 'desc')
        ->get();

        return view('reports.vital-signs', [
            'highFever' => ClinicVisit::where('temperature', '>', 38)->count(),
            'lowTemperature' => ClinicVisit::where('temperature', '<', 36)->count(),
            'lowOxygen' => ClinicVisit::where('spo2', '<', 95)->count(),
            'abnormalReadings' => $abnormal,
        ]);
    }

    public function download($type)
    {
        $filename = match ($type) {
            'patients' => 'patients-report.csv',
            'clinic-visits' => 'clinic-visits-report.csv',
            'diagnosis' => 'diagnosis-report.csv',
            'medicines' => 'medicines-report.csv',
            'appointments' => 'appointments-report.csv',
            'vital-signs' => 'vital-signs-report.csv',
            default => null,
        };

        if (!$filename) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = match ($type) {
            'patients' => $this->exportPatients(),
            'clinic-visits' => $this->exportClinicVisits(),
            'diagnosis' => $this->exportDiagnosis(),
            'medicines' => $this->exportMedicines(),
            'appointments' => $this->exportAppointments(),
            'vital-signs' => $this->exportVitalSigns(),
            default => null,
        };

        if (!$callback) {
            abort(404);
        }

        return response()->stream($callback, 200, $headers);
    }

    protected function outputCsv($rows, $headers)
    {
        $callback = function () use ($rows, $headers) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return $callback;
    }

    protected function exportPatients()
    {
        $patients = Patient::with('clinicVisits')
            ->withCount('clinicVisits')
            ->orderBy('name')
            ->get();

        $rows = $patients->map(function ($patient) {
            return [
                'Name' => $patient->name,
                'Category' => ucfirst($patient->category),
                'Year/Section' => $patient->year_section ?? 'N/A',
                'Age' => $patient->age ?? 'N/A',
                'Phone' => $patient->phone ?? 'N/A',
                'Email' => $patient->email ?? 'N/A',
                'Address' => $patient->address ?? 'N/A',
                'Program' => $patient->program ?? 'N/A',
                'Status' => ucfirst($patient->status),
                'Total Visits' => $patient->clinic_visits_count,
            ];
        })->toArray();

        return $this->outputCsv($rows, [
            'Name', 'Category', 'Year/Section', 'Age', 'Phone', 'Email', 'Address', 'Program', 'Status', 'Total Visits'
        ]);
    }

    protected function exportClinicVisits()
    {
        $visits = ClinicVisit::with('patient')
            ->orderBy('visit_date', 'desc')
            ->get();

        $rows = $visits->map(function ($visit) {
            $date = \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d');

            return [
                'Date' => $date,
                'Patient' => $visit->patient->name ?? 'N/A',
                'Category' => $visit->patient->category ?? 'N/A',
                'Year/Section' => $visit->patient->year_section ?? 'N/A',
                'Temperature' => $visit->temperature ? $visit->temperature . '°C' : '-',
                'Pulse Rate' => $visit->pulse_rate ?: '-',
                'Respiratory Rate' => $visit->respiratory_rate ?: '-',
                'Blood Pressure' => ($visit->bp_systolic && $visit->bp_diastolic) ? $visit->bp_systolic . '/' . $visit->bp_diastolic . ' mmHg' : '-',
                'Height' => $visit->height ? $visit->height . ' cm' : '-',
                'Weight' => $visit->weight ? $visit->weight . ' kg' : '-',
                'BMI' => $visit->getBMI() ?: '-',
                'SpO2' => $visit->spo2 ? $visit->spo2 . '%' : '-',
                'Complaints' => $visit->complaints ?: '-',
                'Diagnosis' => $visit->diagnosis ?: '-',
                'Management' => $visit->management ?: '-',
                'Notes' => $visit->notes ?: '-',
            ];
        })->toArray();

        return $this->outputCsv($rows, [
            'Date', 'Patient', 'Category', 'Year/Section', 'Temperature', 'Pulse Rate', 'Respiratory Rate',
            'Blood Pressure', 'Height', 'Weight', 'BMI', 'SpO2', 'Complaints', 'Diagnosis', 'Management', 'Notes'
        ]);
    }

    protected function exportDiagnosis()
    {
        $diagnoses = ClinicVisit::select('diagnosis')
            ->whereNotNull('diagnosis')
            ->where('diagnosis', '!=', '')
            ->groupBy('diagnosis')
            ->selectRaw('diagnosis, COUNT(*) as count')
            ->orderByRaw('COUNT(*) DESC')
            ->get();

        $rows = $diagnoses->map(function ($item) {
            return [
                'Diagnosis' => $item->diagnosis,
                'Count' => $item->count,
            ];
        })->toArray();

        return $this->outputCsv($rows, ['Diagnosis', 'Count']);
    }

    protected function exportMedicines()
    {
        $medicines = Medicine::where('status', 'active')
            ->orderBy('quantity')
            ->get();

        $rows = $medicines->map(function ($medicine) {
            return [
                'Name' => $medicine->name,
                'Category' => $medicine->category ?? 'N/A',
                'Quantity' => $medicine->quantity,
                'Minimum Stock' => $medicine->minimum_stock,
                'Unit' => $medicine->unit ?? 'N/A',
                'Status' => $medicine->quantity <= 0 ? 'Out of Stock' : ($medicine->quantity <= $medicine->minimum_stock ? 'Low Stock' : 'In Stock'),
            ];
        })->toArray();

        return $this->outputCsv($rows, ['Name', 'Category', 'Quantity', 'Minimum Stock', 'Unit', 'Status']);
    }

    protected function exportAppointments()
    {
        $appointments = Appointment::with('patient')
            ->orderBy('appointment_date', 'desc')
            ->get();

        $rows = $appointments->map(function ($appointment) {
            $date = \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d');

            return [
                'Date' => $date,
                'Time' => $appointment->appointment_time ?? 'N/A',
                'Patient' => $appointment->patient->name ?? 'N/A',
                'Category' => $appointment->patient->category ?? 'N/A',
                'Reason' => $appointment->reason ?? 'N/A',
                'Status' => ucfirst($appointment->status),
                'Notes' => $appointment->notes ?? 'N/A',
            ];
        })->toArray();

        return $this->outputCsv($rows, ['Date', 'Time', 'Patient', 'Category', 'Reason', 'Status', 'Notes']);
    }

    protected function exportVitalSigns()
    {
        $abnormal = ClinicVisit::where(function ($query) {
            $query->where('temperature', '>', 38)
                  ->orWhere('temperature', '<', 36)
                  ->orWhere('spo2', '<', 95);
        })->with('patient')
        ->orderBy('visit_date', 'desc')
        ->get();

        $rows = $abnormal->map(function ($visit) {
            $alerts = [];
            if ($visit->temperature > 38) $alerts[] = 'High Fever';
            if ($visit->temperature < 36) $alerts[] = 'Low Temperature';
            if ($visit->spo2 < 95) $alerts[] = 'Low Oxygen';

            $date = \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d');

            return [
                'Date' => $date,
                'Patient' => $visit->patient->name ?? 'N/A',
                'Category' => $visit->patient->category ?? 'N/A',
                'Temperature' => $visit->temperature ? $visit->temperature . '°C' : '-',
                'SpO2' => $visit->spo2 ? $visit->spo2 . '%' : '-',
                'Alert' => implode(', ', $alerts),
            ];
        })->toArray();

        return $this->outputCsv($rows, ['Date', 'Patient', 'Category', 'Temperature', 'SpO2', 'Alert']);
    }
}