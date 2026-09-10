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
    // ── Shared date parser ─────────────────────────────────────────
    private function parseDateRange(Request $request): array
    {
        $dateFrom = null;
        $dateTo   = null;

        if ($request->filled('date')) {
            $dateFrom = Carbon::parse($request->date)->startOfDay();
            $dateTo   = Carbon::parse($request->date)->endOfDay();
        }

        // Quick preset overrides explicit date
        if ($request->preset === 'today') {
            $dateFrom = now()->startOfDay();
            $dateTo   = now()->endOfDay();
        } elseif ($request->preset === 'week') {
            $dateFrom = now()->startOfWeek();
            $dateTo   = now()->endOfWeek();
        } elseif ($request->preset === 'month') {
            $dateFrom = now()->startOfMonth();
            $dateTo   = now()->endOfMonth();
        }

        return [$dateFrom, $dateTo];
    }

    private function applyDateFilter($query, ?Carbon $dateFrom, ?Carbon $dateTo, string $column = 'visit_date')
    {
        if ($dateFrom && $dateTo) {
            $query->whereBetween($column, [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $query->where($column, '>=', $dateFrom);
        } elseif ($dateTo) {
            $query->where($column, '<=', $dateTo);
        }
        return $query;
    }

    public function patients(Request $request)
    {
        [$dateFrom, $dateTo] = $this->parseDateRange($request);

        // Patient list — filter by first visit date if date range given
        $patientsQuery = Patient::with('clinicVisits')->withCount('clinicVisits')->orderBy('name');
        if ($dateFrom || $dateTo) {
            $patientsQuery->whereHas('clinicVisits', function ($q) use ($dateFrom, $dateTo) {
                $this->applyDateFilter($q, $dateFrom, $dateTo);
            });
        }

        $baseQuery = fn() => Patient::query();
        $filteredQuery = (clone $baseQuery())->when($dateFrom || $dateTo, fn($q) => $q->whereHas('clinicVisits', function ($q2) use ($dateFrom, $dateTo) {
            $this->applyDateFilter($q2, $dateFrom, $dateTo);
        }));

        return view('reports.patients', [
            'totalPatients'    => $filteredQuery->count(),
            'students'         => (clone $baseQuery())->where('category', 'student')->count(),
            'faculty'          => (clone $baseQuery())->where('category', 'faculty')->count(),
            'staff'            => (clone $baseQuery())->where('category', 'staff')->count(),
            'activePatients'   => (clone $baseQuery())->where('status', 'active')->count(),
            'inactivePatients' => (clone $baseQuery())->where('status', 'inactive')->count(),
            'patients'         => $patientsQuery->get(),
            'date'          => $request->date ?? '',
            'preset'           => $request->preset ?? '',
            'filteredCount'    => $patientsQuery->count(),
        ]);
    }

    public function clinicVisits(Request $request)
    {
        [$dateFrom, $dateTo] = $this->parseDateRange($request);

        $last30Days = collect(range(29, 0))->map(function ($days) {
            $date = now()->subDays($days);
            return [
                'date'  => $date->format('M d'),
                'count' => ClinicVisit::whereDate('visit_date', $date)->count(),
            ];
        });

        $visitsQuery = ClinicVisit::with('patient')->orderBy('visit_date', 'desc');
        $this->applyDateFilter($visitsQuery, $dateFrom, $dateTo);

        $baseQuery = fn() => ClinicVisit::query();
        $filteredQuery = (clone $baseQuery())->when($dateFrom || $dateTo, fn($q) => $this->applyDateFilter($q, $dateFrom, $dateTo));

        return view('reports.clinic-visits', [
            'totalVisits'    => $filteredQuery->count(),
            'todayVisits'    => (clone $baseQuery())->whereDate('visit_date', today())->count(),
            'monthVisits'    => (clone $baseQuery())->whereBetween('visit_date', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'uniquePatients' => (clone $baseQuery())->distinct('patient_id')->count('patient_id'),
            'last30Days'     => $last30Days,
            'recentVisits'   => $visitsQuery->get(),
            'date'          => $request->date ?? '',
            'preset'         => $request->preset ?? '',
            'filteredCount'  => $visitsQuery->count(),
        ]);
    }

    public function diagnosis(Request $request)
    {
        [$dateFrom, $dateTo] = $this->parseDateRange($request);

        $diagQuery = ClinicVisit::select('diagnosis')
            ->whereNotNull('diagnosis')->where('diagnosis', '!=', '');
        $this->applyDateFilter($diagQuery, $dateFrom, $dateTo);
        $topDiagnoses = (clone $diagQuery)
            ->groupBy('diagnosis')
            ->selectRaw('diagnosis, COUNT(*) as count')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)->get();

        $baseQuery = fn() => ClinicVisit::whereNotNull('diagnosis')->where('diagnosis', '!=', '');
        $filteredQuery = (clone $baseQuery())->when($dateFrom || $dateTo, fn($q) => $this->applyDateFilter($q, $dateFrom, $dateTo));

        return view('reports.diagnosis', [
            'topDiagnoses'         => $topDiagnoses,
            'totalUniqueDiagnoses' => $filteredQuery->distinct('diagnosis')->count('diagnosis'),
            'date'          => $request->date ?? '',
            'preset'               => $request->preset ?? '',
            'filteredCount'        => $topDiagnoses->sum('count'),
        ]);
    }

    public function medicines(Request $request)
    {
        // Medicines have no visit date — we keep as-is, date just shown in UI
        [$dateFrom, $dateTo] = $this->parseDateRange($request);

        $medicines = Medicine::where('status', 'active')->orderBy('quantity')->get();

        return view('reports.medicines', [
            'totalMedicines' => Medicine::count(),
            'lowStock'       => Medicine::whereRaw('quantity <= minimum_stock')->count(),
            'outOfStock'     => Medicine::where('quantity', '<=', 0)->count(),
            'medicines'      => $medicines,
            'date'          => $request->date ?? '',
            'preset'         => $request->preset ?? '',
        ]);
    }

    public function appointments(Request $request)
    {
        [$dateFrom, $dateTo] = $this->parseDateRange($request);

        $apptQuery = Appointment::with('patient')->orderBy('appointment_date', 'desc');
        $this->applyDateFilter($apptQuery, $dateFrom, $dateTo, 'appointment_date');

        $baseQuery = fn() => Appointment::query();
        $filteredQuery = (clone $baseQuery())->when($dateFrom || $dateTo, fn($q) => $this->applyDateFilter($q, $dateFrom, $dateTo, 'appointment_date'));

        return view('reports.appointments', [
            'totalAppointments' => $filteredQuery->count(),
            'scheduled'         => (clone $baseQuery())->where('status', 'scheduled')->count(),
            'completed'         => (clone $baseQuery())->where('status', 'completed')->count(),
            'noShow'            => (clone $baseQuery())->where('status', 'no-show')->count(),
            'cancelled'         => (clone $baseQuery())->where('status', 'cancelled')->count(),
            'appointments'      => $apptQuery->get(),
            'date'          => $request->date ?? '',
            'preset'            => $request->preset ?? '',
            'filteredCount'     => $apptQuery->count(),
        ]);
    }

    public function vitalSigns(Request $request)
    {
        [$dateFrom, $dateTo] = $this->parseDateRange($request);

        $baseQuery = fn() => ClinicVisit::where(function ($q) {
            $q->whereNotNull('temperature')
              ->orWhereNotNull('pulse_rate')
              ->orWhereNotNull('respiratory_rate')
              ->orWhereNotNull('bp_systolic')
              ->orWhereNotNull('spo2')
              ->orWhereNotNull('height')
              ->orWhereNotNull('weight');
        });

        $readingsQuery = $baseQuery()->orderBy('visit_date', 'desc');
        $this->applyDateFilter($readingsQuery, $dateFrom, $dateTo);
        $allReadings = $readingsQuery->with('patient')->get();

        // Summary counts (respect date filter)
        $cntQ = fn() => tap($baseQuery(), fn($q) => $this->applyDateFilter($q, $dateFrom, $dateTo));

        $highFever      = (clone $cntQ())->whereNotNull('temperature')->where('temperature', '>=', 38.0)->count();
        $lowTemperature = (clone $cntQ())->whereNotNull('temperature')->where('temperature', '<', 36.0)->count();
        $criticalTemp   = (clone $cntQ())->whereNotNull('temperature')
            ->where(fn($q) => $q->where('temperature', '<', 35.0)->orWhere('temperature', '>=', 38.0))->count();

        $lowOxygen      = (clone $cntQ())->whereNotNull('spo2')->where('spo2', '<=', 92)->count();
        $criticalOxygen = (clone $cntQ())->whereNotNull('spo2')->where('spo2', '<=', 90)->count();

        $highBP     = (clone $cntQ())->where(fn($q) => $q->where('bp_systolic', '>=', 140)->orWhere('bp_diastolic', '>=', 90))->count();
        $lowBP      = (clone $cntQ())->where(fn($q) => $q->where('bp_systolic', '<', 90)->orWhere('bp_diastolic', '<', 60))->count();
        $criticalBP = (clone $cntQ())->where(fn($q) => $q->where('bp_systolic', '>=', 180)->orWhere('bp_diastolic', '>=', 120)
            ->orWhere('bp_systolic', '<', 80)->orWhere('bp_diastolic', '<', 50))->count();

        $highPulse     = (clone $cntQ())->whereNotNull('pulse_rate')->where('pulse_rate', '>', 100)->count();
        $lowPulse      = (clone $cntQ())->whereNotNull('pulse_rate')->where('pulse_rate', '<', 60)->count();
        $criticalPulse = (clone $cntQ())->whereNotNull('pulse_rate')
            ->where(fn($q) => $q->where('pulse_rate', '<', 50)->orWhere('pulse_rate', '>', 120))->count();

        $highRespRate     = (clone $cntQ())->whereNotNull('respiratory_rate')->where('respiratory_rate', '>', 20)->count();
        $lowRespRate      = (clone $cntQ())->whereNotNull('respiratory_rate')->where('respiratory_rate', '<', 10)->count();
        $criticalRespRate = (clone $cntQ())->whereNotNull('respiratory_rate')
            ->where(fn($q) => $q->where('respiratory_rate', '<', 8)->orWhere('respiratory_rate', '>', 30))->count();

        $normalCount = $aboveCount = $belowCount = $abnormalCount = 0;
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
        ) + [
            'date'          => $request->date ?? '',
            'preset'        => $request->preset ?? '',
            'filteredCount' => $allReadings->count(),
        ]);
    }

    public function download($type, Request $request)
    {
        [$dateFrom, $dateTo] = $this->parseDateRange($request);

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
            'patients'      => $this->exportPatients($dateFrom, $dateTo),
            'clinic-visits' => $this->exportClinicVisits($dateFrom, $dateTo),
            'diagnosis'     => $this->exportDiagnosis($dateFrom, $dateTo),
            'medicines'     => $this->exportMedicines($dateFrom, $dateTo),
            'appointments'  => $this->exportAppointments($dateFrom, $dateTo),
            'vital-signs'   => $this->exportVitalSigns($dateFrom, $dateTo),
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

    protected function exportPatients(?Carbon $dateFrom, ?Carbon $dateTo)
    {
        $query = Patient::with('clinicVisits')->withCount('clinicVisits')->orderBy('name');
        if ($dateFrom || $dateTo) {
            $query->whereHas('clinicVisits', function ($q) use ($dateFrom, $dateTo) {
                $this->applyDateFilter($q, $dateFrom, $dateTo);
            });
        }
        $patients = $query->get();
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

    protected function exportClinicVisits(?Carbon $dateFrom, ?Carbon $dateTo)
    {
        $query = ClinicVisit::with('patient')->orderBy('visit_date', 'desc');
        if ($dateFrom || $dateTo) {
            $this->applyDateFilter($query, $dateFrom, $dateTo);
        }
        $visits = $query->get();
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

    protected function exportDiagnosis(?Carbon $dateFrom, ?Carbon $dateTo)
    {
        $query = ClinicVisit::select('diagnosis')
            ->whereNotNull('diagnosis')->where('diagnosis', '!=', '');
        if ($dateFrom || $dateTo) {
            $this->applyDateFilter($query, $dateFrom, $dateTo);
        }
        $diagnoses = $query
            ->groupBy('diagnosis')
            ->selectRaw('diagnosis, COUNT(*) as count')
            ->orderByRaw('COUNT(*) DESC')
            ->get();

        return $this->outputCsv(
            $diagnoses->map(fn($i) => ['Diagnosis' => $i->diagnosis, 'Count' => $i->count])->toArray(),
            ['Diagnosis', 'Count']
        );
    }

    protected function exportMedicines(?Carbon $dateFrom, ?Carbon $dateTo)
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

    protected function exportAppointments(?Carbon $dateFrom, ?Carbon $dateTo)
    {
        $query = Appointment::with('patient')->orderBy('appointment_date', 'desc');
        if ($dateFrom || $dateTo) {
            $this->applyDateFilter($query, $dateFrom, $dateTo, 'appointment_date');
        }
        $appointments = $query->get();
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

    protected function exportVitalSigns(?Carbon $dateFrom, ?Carbon $dateTo)
    {
        $query = ClinicVisit::with('patient')
            ->where(function ($q) {
                $q->whereNotNull('temperature')->orWhereNotNull('pulse_rate')
                  ->orWhereNotNull('respiratory_rate')->orWhereNotNull('bp_systolic')
                  ->orWhereNotNull('spo2')->orWhereNotNull('height')->orWhereNotNull('weight');
            });
        if ($dateFrom || $dateTo) {
            $this->applyDateFilter($query, $dateFrom, $dateTo);
        }
        $visits = $query->orderBy('visit_date', 'desc')->get();

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

        $pdf->setPaper('legal', 'landscape');

        return $pdf->download('clinic-report-' . $reportType . '-' . now()->format('Y-m-d') . '.pdf');
    }
}