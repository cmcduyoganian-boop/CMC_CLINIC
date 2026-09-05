<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\ClinicVisit;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Support\VitalSigns;
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
            'totalPatients'   => Patient::count(),
            'students'        => Patient::where('category', 'student')->count(),
            'faculty'         => Patient::where('category', 'faculty')->count(),
            'staff'           => Patient::where('category', 'staff')->count(),
            'activePatients'  => Patient::where('status', 'active')->count(),
            'inactivePatients'=> Patient::where('status', 'inactive')->count(),
            'patients'        => $patients,
        ]);
    }

    public function clinicVisits()
    {
        $last30Days = collect(range(29, 0))->map(function ($days) {
            $date = now()->subDays($days);
            return [
                'date'  => $date->format('M d'),
                'count' => ClinicVisit::whereDate('visit_date', $date)->count(),
            ];
        });

        return view('reports.clinic-visits', [
            'totalVisits'    => ClinicVisit::count(),
            'todayVisits'    => ClinicVisit::whereDate('visit_date', today())->count(),
            'monthVisits'    => ClinicVisit::whereBetween('visit_date', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])->count(),
            'uniquePatients' => ClinicVisit::distinct('patient_id')->count('patient_id'),
            'last30Days'     => $last30Days,
            'recentVisits'   => ClinicVisit::with('patient')
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
            'topDiagnoses'        => $topDiagnoses,
            'totalUniqueDiagnoses'=> ClinicVisit::whereNotNull('diagnosis')
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
            'lowStock'       => Medicine::whereRaw('quantity <= minimum_stock')->count(),
            'outOfStock'     => Medicine::where('quantity', '<=', 0)->count(),
            'medicines'      => $medicines,
        ]);
    }

    public function appointments()
    {
        return view('reports.appointments', [
            'totalAppointments' => Appointment::count(),
            'scheduled'         => Appointment::where('status', 'scheduled')->count(),
            'completed'         => Appointment::where('status', 'completed')->count(),
            'noShow'            => Appointment::where('status', 'no-show')->count(),
            'cancelled'         => Appointment::where('status', 'cancelled')->count(),
            'appointments'      => Appointment::with('patient')
                ->orderBy('appointment_date', 'desc')
                ->limit(20)
                ->get(),
        ]);
    }

    public function vitalSigns()
    {
        // Fetch ALL visits that have at least one vital sign recorded
        $allReadings = ClinicVisit::with('patient')
            ->where(function ($q) {
                $q->whereNotNull('temperature')
                  ->orWhereNotNull('pulse_rate')
                  ->orWhereNotNull('respiratory_rate')
                  ->orWhereNotNull('bp_systolic')
                  ->orWhereNotNull('spo2')
                  ->orWhereNotNull('height')
                  ->orWhereNotNull('weight');
            })
            ->orderBy('visit_date', 'desc')
            ->get();

        // Build summary counts using correct VitalSigns thresholds
        // ── Temperature ──
        $highFever      = ClinicVisit::whereNotNull('temperature')->where('temperature', '>=', 38.0)->count();
        $lowTemperature = ClinicVisit::whereNotNull('temperature')->where('temperature', '<', 36.0)->count();
        $criticalTemp   = ClinicVisit::whereNotNull('temperature')
            ->where(fn($q) => $q->where('temperature', '<', 35.0)->orWhere('temperature', '>=', 38.0))
            ->count();

        // ── SpO₂ ──
        $lowOxygen      = ClinicVisit::whereNotNull('spo2')->where('spo2', '<=', 92)->count();
        $criticalOxygen = ClinicVisit::whereNotNull('spo2')->where('spo2', '<=', 90)->count();

        // ── Blood Pressure ──
        $highBP = ClinicVisit::where(fn($q) => $q
            ->where('bp_systolic', '>=', 140)->orWhere('bp_diastolic', '>=', 90))->count();
        $lowBP  = ClinicVisit::where(fn($q) => $q
            ->where('bp_systolic', '<', 90)->orWhere('bp_diastolic', '<', 60))->count();
        $criticalBP = ClinicVisit::where(fn($q) => $q
            ->where('bp_systolic', '>=', 180)->orWhere('bp_diastolic', '>=', 120)
            ->orWhere('bp_systolic', '<', 80)->orWhere('bp_diastolic', '<', 50))->count();

        // ── Pulse Rate ──
        $highPulse    = ClinicVisit::whereNotNull('pulse_rate')->where('pulse_rate', '>', 100)->count();
        $lowPulse     = ClinicVisit::whereNotNull('pulse_rate')->where('pulse_rate', '<', 60)->count();
        $criticalPulse = ClinicVisit::whereNotNull('pulse_rate')
            ->where(fn($q) => $q->where('pulse_rate', '<', 50)->orWhere('pulse_rate', '>', 120))
            ->count();

        // ── Respiratory Rate ──
        $highRespRate    = ClinicVisit::whereNotNull('respiratory_rate')->where('respiratory_rate', '>', 20)->count();
        $lowRespRate     = ClinicVisit::whereNotNull('respiratory_rate')->where('respiratory_rate', '<', 10)->count();
        $criticalRespRate = ClinicVisit::whereNotNull('respiratory_rate')
            ->where(fn($q) => $q->where('respiratory_rate', '<', 8)->orWhere('respiratory_rate', '>', 30))
            ->count();

        // Overall counts per classification
        $normalCount   = 0;
        $aboveCount    = 0;
        $belowCount    = 0;
        $abnormalCount = 0;

        foreach ($allReadings as $r) {
            $overall = $r->getVitalSignsAssessment()['overall'];
            match ($overall) {
                VitalSigns::ABNORMAL     => $abnormalCount++,
                VitalSigns::ABOVE_NORMAL => $aboveCount++,
                VitalSigns::BELOW_NORMAL => $belowCount++,
                default                  => $normalCount++,
            };
        }

        return view('reports.vital-signs', compact(
            'allReadings',
            'highFever', 'lowTemperature', 'criticalTemp',
            'lowOxygen', 'criticalOxygen',
            'highBP', 'lowBP', 'criticalBP',
            'highPulse', 'lowPulse', 'criticalPulse',
            'highRespRate', 'lowRespRate', 'criticalRespRate',
            'normalCount', 'aboveCount', 'belowCount', 'abnormalCount',
        ));
    }

    public function download($type)
    {
        $filename = match ($type) {
            'patients'      => 'patients-report.csv',
            'clinic-visits' => 'clinic-visits-report.csv',
            'diagnosis'     => 'diagnosis-report.csv',
            'medicines'     => 'medicines-report.csv',
            'appointments'  => 'appointments-report.csv',
            'vital-signs'   => 'vital-signs-report.csv',
            default         => null,
        };

        if (!$filename) abort(404);

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = match ($type) {
            'patients'      => $this->exportPatients(),
            'clinic-visits' => $this->exportClinicVisits(),
            'diagnosis'     => $this->exportDiagnosis(),
            'medicines'     => $this->exportMedicines(),
            'appointments'  => $this->exportAppointments(),
            'vital-signs'   => $this->exportVitalSigns(),
            default         => null,
        };

        if (!$callback) abort(404);

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
        $patients = Patient::with('clinicVisits')->withCount('clinicVisits')->orderBy('name')->get();
        $rows = $patients->map(fn($p) => [
            'Name'         => $p->name,
            'Category'     => ucfirst($p->category),
            'Year/Section' => $p->year_section ?? 'N/A',
            'Age'          => $p->age ?? 'N/A',
            'Phone'        => $p->phone ?? 'N/A',
            'Email'        => $p->email ?? 'N/A',
            'Address'      => $p->address ?? 'N/A',
            'Program'      => $p->program ?? 'N/A',
            'Status'       => ucfirst($p->status),
            'Total Visits' => $p->clinic_visits_count,
        ])->toArray();

        return $this->outputCsv($rows, [
            'Name','Category','Year/Section','Age','Phone','Email','Address','Program','Status','Total Visits'
        ]);
    }

    protected function exportClinicVisits()
    {
        $visits = ClinicVisit::with('patient')->orderBy('visit_date', 'desc')->get();
        $rows = $visits->map(fn($v) => [
            'Date'             => Carbon::parse($v->visit_date)->format('Y-m-d'),
            'Patient'          => $v->patient->name ?? 'N/A',
            'Category'         => $v->patient->category ?? 'N/A',
            'Year/Section'     => $v->patient->year_section ?? 'N/A',
            'Temperature'      => $v->temperature ? $v->temperature . '°C' : '-',
            'Pulse Rate'       => $v->pulse_rate ?: '-',
            'Respiratory Rate' => $v->respiratory_rate ?: '-',
            'Blood Pressure'   => ($v->bp_systolic && $v->bp_diastolic) ? $v->bp_systolic . '/' . $v->bp_diastolic . ' mmHg' : '-',
            'Height'           => $v->height ? $v->height . ' cm' : '-',
            'Weight'           => $v->weight ? $v->weight . ' kg' : '-',
            'BMI'              => $v->getBMI() ?: '-',
            'SpO2'             => $v->spo2 ? $v->spo2 . '%' : '-',
            'Complaints'       => $v->complaints ?: '-',
            'Diagnosis'        => $v->diagnosis ?: '-',
            'Management'       => $v->management ?: '-',
            'Notes'            => $v->notes ?: '-',
        ])->toArray();

        return $this->outputCsv($rows, [
            'Date','Patient','Category','Year/Section','Temperature','Pulse Rate','Respiratory Rate',
            'Blood Pressure','Height','Weight','BMI','SpO2','Complaints','Diagnosis','Management','Notes'
        ]);
    }

    protected function exportDiagnosis()
    {
        $diagnoses = ClinicVisit::select('diagnosis')
            ->whereNotNull('diagnosis')->where('diagnosis', '!=', '')
            ->groupBy('diagnosis')->selectRaw('diagnosis, COUNT(*) as count')
            ->orderByRaw('COUNT(*) DESC')->get();

        return $this->outputCsv(
            $diagnoses->map(fn($i) => ['Diagnosis' => $i->diagnosis, 'Count' => $i->count])->toArray(),
            ['Diagnosis', 'Count']
        );
    }

    protected function exportMedicines()
    {
        $medicines = Medicine::where('status', 'active')->orderBy('quantity')->get();
        $rows = $medicines->map(fn($m) => [
            'Name'          => $m->name,
            'Category'      => $m->category ?? 'N/A',
            'Quantity'      => $m->quantity,
            'Minimum Stock' => $m->minimum_stock,
            'Unit'          => $m->unit ?? 'N/A',
            'Status'        => $m->quantity <= 0 ? 'Out of Stock' : ($m->quantity <= $m->minimum_stock ? 'Low Stock' : 'In Stock'),
        ])->toArray();

        return $this->outputCsv($rows, ['Name','Category','Quantity','Minimum Stock','Unit','Status']);
    }

    protected function exportAppointments()
    {
        $appointments = Appointment::with('patient')->orderBy('appointment_date', 'desc')->get();
        $rows = $appointments->map(fn($a) => [
            'Date'     => Carbon::parse($a->appointment_date)->format('Y-m-d'),
            'Time'     => $a->appointment_time ?? 'N/A',
            'Patient'  => $a->patient->name ?? 'N/A',
            'Category' => $a->patient->category ?? 'N/A',
            'Reason'   => $a->reason ?? 'N/A',
            'Status'   => ucfirst($a->status),
            'Notes'    => $a->notes ?? 'N/A',
        ])->toArray();

        return $this->outputCsv($rows, ['Date','Time','Patient','Category','Reason','Status','Notes']);
    }

    protected function exportVitalSigns()
    {
        $visits = ClinicVisit::with('patient')
            ->where(function ($q) {
                $q->whereNotNull('temperature')->orWhereNotNull('pulse_rate')
                  ->orWhereNotNull('respiratory_rate')->orWhereNotNull('bp_systolic')
                  ->orWhereNotNull('spo2')->orWhereNotNull('height')->orWhereNotNull('weight');
            })
            ->orderBy('visit_date', 'desc')
            ->get();

        $rows = $visits->map(function ($v) {
            $a   = $v->getVitalSignsAssessment();
            $st  = $a['statuses'];
            $bmi = $v->getBMI();

            $lbl = fn(?string $s) => $s ? VitalSigns::label($s) : '-';

            return [
                'Date'                    => Carbon::parse($v->visit_date)->format('Y-m-d'),
                'Patient'                 => $v->patient->name ?? 'N/A',
                'Category'                => ucfirst($v->patient->category ?? 'N/A'),
                'Temperature'             => $v->temperature ? $v->temperature . '°C' : '-',
                'Temperature Status'      => $lbl($st['temperature']),
                'Pulse Rate'              => $v->pulse_rate ? $v->pulse_rate . ' bpm' : '-',
                'Pulse Rate Status'       => $lbl($st['pulse_rate']),
                'Respiratory Rate'        => $v->respiratory_rate ? $v->respiratory_rate . ' /min' : '-',
                'Respiratory Rate Status' => $lbl($st['respiratory_rate']),
                'Systolic BP'             => $v->bp_systolic ? $v->bp_systolic . ' mmHg' : '-',
                'Systolic Status'         => $lbl($st['bp_systolic']),
                'Diastolic BP'            => $v->bp_diastolic ? $v->bp_diastolic . ' mmHg' : '-',
                'Diastolic Status'        => $lbl($st['bp_diastolic']),
                'SpO2'                    => $v->spo2 ? $v->spo2 . '%' : '-',
                'SpO2 Status'             => $lbl($st['spo2']),
                'BMI'                     => $bmi ?: '-',
                'BMI Status'              => $lbl($st['bmi']),
                'Overall VS Assessment'   => $lbl($a['overall']),
                'Diagnosis'               => $v->diagnosis ?: '-',
            ];
        })->toArray();

        return $this->outputCsv($rows, [
            'Date','Patient','Category',
            'Temperature','Temperature Status',
            'Pulse Rate','Pulse Rate Status',
            'Respiratory Rate','Respiratory Rate Status',
            'Systolic BP','Systolic Status',
            'Diastolic BP','Diastolic Status',
            'SpO2','SpO2 Status',
            'BMI','BMI Status',
            'Overall VS Assessment','Diagnosis',
        ]);
    }

    public function clinicReportPdf(Request $request)
    {
        $reportType = $request->query('type', 'weekly');
        $startDate = $request->query('start', now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->query('end', now()->endOfWeek()->format('Y-m-d'));

        $component = new \App\Livewire\Reports\ClinicReport();
        $component->reportType = $reportType;
        $component->startDate = $startDate;
        $component->endDate = $endDate;
        $component->computeReport();

        $sanitize = function (?string $value): string {
            if ($value === null) return '';
            $value = (string) $value;
            if (!mb_check_encoding($value, 'UTF-8')) {
                $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            }
            return $value;
        };

        $reportRows = collect($component->reportRows)->map(function ($row) use ($sanitize) {
            return [
                'date_label' => $sanitize($row['date_label']),
                'male' => (int) $row['male'],
                'female' => (int) $row['female'],
                'bsis1' => (int) $row['bsis1'],
                'bsis2' => (int) $row['bsis2'],
                'bsis3' => (int) $row['bsis3'],
                'bsis4' => (int) $row['bsis4'],
                'faculty_admin' => (int) $row['faculty_admin'],
                'carmenanon' => (int) $row['carmenanon'],
                'non_carmenanon' => (int) $row['non_carmenanon'],
                'complaints' => $sanitize($row['complaints'] ?? ''),
                'medicines' => $sanitize($row['medicines'] ?? ''),
                'services' => $sanitize($row['services'] ?? ''),
            ];
        })->toArray();

        $grandTotals = [
            'male' => (int) ($component->grandTotals['male'] ?? 0),
            'female' => (int) ($component->grandTotals['female'] ?? 0),
            'bsis1' => (int) ($component->grandTotals['bsis1'] ?? 0),
            'bsis2' => (int) ($component->grandTotals['bsis2'] ?? 0),
            'bsis3' => (int) ($component->grandTotals['bsis3'] ?? 0),
            'bsis4' => (int) ($component->grandTotals['bsis4'] ?? 0),
            'faculty_admin' => (int) ($component->grandTotals['faculty_admin'] ?? 0),
            'carmenanon' => (int) ($component->grandTotals['carmenanon'] ?? 0),
            'non_carmenanon' => (int) ($component->grandTotals['non_carmenanon'] ?? 0),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.clinic-report', [
            'reportType' => $sanitize($reportType),
            'startDate' => $sanitize($startDate),
            'endDate' => $sanitize($endDate),
            'reportRows' => $reportRows,
            'grandTotals' => $grandTotals,
        ]);

        return $pdf->download('clinic-report-' . $reportType . '-' . now()->format('Y-m-d') . '.pdf');
    }
}