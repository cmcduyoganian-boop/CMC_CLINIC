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
}