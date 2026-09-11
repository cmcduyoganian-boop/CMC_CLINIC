<?php

namespace App\Livewire\Reports;

use App\Models\ClinicVisit;
use App\Models\MedicineInventoryLog;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\StudentHealthRecord;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClinicReport extends Component
{
    public string $reportType = 'weekly';
    public string $startDate = '';
    public string $endDate = '';
    public array $reportRows = [];
    public array $grandTotals = [];

    protected $listeners = ['refreshReport' => 'computeReport'];

    public function mount()
    {
        $this->startDate = now()->startOfWeek()->format('Y-m-d');
        $this->endDate = now()->endOfWeek()->format('Y-m-d');
        $this->computeReport();
    }

    public function updatedReportType()
    {
        $this->syncDatesToType();
        $this->computeReport();
    }

    public function updatedStartDate()
    {
        $this->computeReport();
    }

    public function updatedEndDate()
    {
        $this->computeReport();
    }

    protected function syncDatesToType(): void
    {
        if ($this->reportType === 'weekly') {
            $this->startDate = now()->startOfWeek()->format('Y-m-d');
            $this->endDate = now()->endOfWeek()->format('Y-m-d');
        } elseif ($this->reportType === 'monthly') {
            $this->startDate = now()->startOfMonth()->format('Y-m-d');
            $this->endDate = now()->endOfMonth()->format('Y-m-d');
        } elseif ($this->reportType === 'semestral') {
            $this->startDate = now()->month >= 8 ? now()->year . '-08-01' : now()->year . '-01-01';
            $this->endDate = now()->month >= 8 ? now()->year . '-12-31' : now()->year . '-06-30';
        } else {
            $this->startDate = now()->startOfWeek()->format('Y-m-d');
            $this->endDate = now()->endOfWeek()->format('Y-m-d');
        }
    }

    protected function getDateRanges(): array
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end, $start];
        }

        return match ($this->reportType) {
            'weekly'   => $this->buildDailyRanges($start, $end),
            'monthly'  => $this->buildMonthlyRanges($start, $end),
            'semestral'=> [['label' => $start->format('M d, Y') . ' - ' . $end->format('M d, Y'), 'start' => $start, 'end' => $end]],
            default    => $this->buildDailyRanges($start, $end),
        };
    }

    protected function buildDailyRanges(Carbon $start, Carbon $end): array
    {
        $ranges = [];
        for ($d = $start->copy(); $d->lessThanOrEqualTo($end); $d->addDay()) {
            $ranges[] = [
                'label' => $d->format('M d, Y'),
                'start' => $d->copy()->startOfDay(),
                'end'   => $d->copy()->endOfDay(),
            ];
        }
        return $ranges;
    }

    protected function buildMonthlyRanges(Carbon $start, Carbon $end): array
    {
        $ranges = [];
        $current = $start->copy()->startOfMonth();
        while ($current->lessThanOrEqualTo($end)) {
            $monthEnd = $current->copy()->endOfMonth();
            if ($monthEnd->greaterThan($end)) {
                $monthEnd = $end->copy()->endOfDay();
            }
            $ranges[] = [
                'label' => $current->format('F Y'),
                'start' => $current->copy()->startOfDay(),
                'end'   => $monthEnd->copy()->endOfDay(),
            ];
            $current->addMonth();
        }
        return $ranges;
    }

    private function cacheKey(): string
    {
        return "report:clinic:{$this->reportType}:{$this->startDate}:{$this->endDate}";
    }

    public function computeReport(): void
    {
        $this->reportRows = Cache::remember($this->cacheKey(), 300, function () {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
            } catch (\Exception $e) {
                return [];
            }

            $ranges = $this->getDateRanges();

            // Single query for all visits in range with patient
            $allVisits = ClinicVisit::with('patient:id,name,category,program,year_section,address,sex,user_id,student_code')
                ->whereBetween('visit_date', [$start, $end])
                ->get();

            // Single query for all medicine logs in range
            $allMedicineLogs = MedicineInventoryLog::whereBetween('created_at', [$start, $end])
                ->where('action', 'used')
                ->with('medicine:id,name')
                ->get();

            // Single query for all student health records for patients in this range
            $patientIds = $allVisits->pluck('patient_id')->filter()->unique();
            $userIds = $allVisits->pluck('patient.user_id')->filter()->unique();
            $studentCodes = $allVisits->pluck('patient.student_code')->filter()->unique();

            $healthRecords = StudentHealthRecord::whereIn('user_id', $userIds)
                ->orWhereIn('student_code', $studentCodes)
                ->latest()
                ->get()
                ->groupBy('user_id');

            // Group visits by date range for efficient processing
            $visitsByRange = $allVisits->groupBy(function ($visit) use ($ranges) {
                foreach ($ranges as $range) {
                    if ($visit->visit_date->between($range['start'], $range['end'])) {
                        return $range['label'];
                    }
                }
                return null;
            });

            // Group medicine logs by date range
            $medsByRange = $allMedicineLogs->groupBy(function ($log) use ($ranges) {
                foreach ($ranges as $range) {
                    if ($log->created_at->between($range['start'], $range['end'])) {
                        return $range['label'];
                    }
                }
                return null;
            });

            $rows = [];
            $grandTotals = [
                'male' => 0, 'female' => 0,
                'bsis1' => 0, 'bsis2' => 0, 'bsis3' => 0, 'bsis4' => 0,
                'faculty_admin' => 0,
                'carmenanon' => 0, 'non_carmenanon' => 0,
                'total' => 0,
            ];

            foreach ($ranges as $range) {
                $visits = $visitsByRange->get($range['label'], collect());
                $medicineLogs = $medsByRange->get($range['label'], collect());

                $row = [
                    'date_label' => $range['label'],
                    'male' => 0,
                    'female' => 0,
                    'bsis1' => 0,
                    'bsis2' => 0,
                    'bsis3' => 0,
                    'bsis4' => 0,
                    'faculty_admin' => 0,
                    'carmenanon' => 0,
                    'non_carmenanon' => 0,
                    'complaints' => [],
                    'medicines' => [],
                    'services' => [],
                ];

                $complaintCounts = [];
                $medicineCounts = [];
                $serviceCounts = [];

                foreach ($visits as $visit) {
                    $patient = $visit->patient;

                    // Sex
                    if ($visit->sex === 'male') {
                        $row['male']++;
                    } elseif ($visit->sex === 'female') {
                        $row['female']++;
                    }

                    // BSIS Year Level
                    if ($patient) {
                        $program = strtoupper((string) ($patient->program ?? ''));
                        $yearSection = strtoupper((string) ($patient->year_section ?? ''));

                        if ($program === 'BSIS' || str_starts_with($yearSection, 'BSIS')) {
                            $year = $this->extractYearLevel($patient->year_section);
                            if ($year === 1) $row['bsis1']++;
                            elseif ($year === 2) $row['bsis2']++;
                            elseif ($year === 3) $row['bsis3']++;
                            elseif ($year === 4) $row['bsis4']++;
                        }
                    }

                    // Faculty/Admin
                    if ($patient && in_array($patient->category, ['faculty', 'staff'], true)) {
                        $row['faculty_admin']++;
                    }

                    // Residency
                    $address = strtolower((string) ($patient->address ?? ''));
                    if (str_contains($address, 'carmen')) {
                        $row['carmenanon']++;
                    } else {
                        $row['non_carmenanon']++;
                    }

                    // Complaints (S&S)
                    if ($visit->complaints) {
                        $complaints = $this->sanitizeString($visit->complaints);
                        $items = array_map('trim', explode(',', $complaints));
                        foreach ($items as $item) {
                            if ($item === '') continue;
                            $key = strtolower($item);
                            $complaintCounts[$key] = ($complaintCounts[$key] ?? 0) + 1;
                        }
                    }

                    // Student Health Record Conditions
                    if ($patient && $patient->user_id) {
                        $healthRecord = $healthRecords->get($patient->user_id, collect())->first();
                        if ($healthRecord) {
                            $pmh = $healthRecord->past_medical_history ?? [];
                            $fh = $healthRecord->family_history ?? [];

                            $conditions = array_filter(array_merge(
                                array_filter($pmh, fn($v, $k) => $v === true && $k !== 'none_medical', ARRAY_FILTER_USE_BOTH),
                                array_filter($fh, fn($v, $k) => $v === true && $k !== 'none', ARRAY_FILTER_USE_BOTH)
                            ), fn($v) => $v === true, ARRAY_FILTER_USE_BOTH);

                            foreach (array_keys($conditions) as $condition) {
                                $label = str_replace('_', ' ', $condition);
                                $key = strtolower($label);
                                $complaintCounts[$key] = ($complaintCounts[$key] ?? 0) + 1;
                            }
                        }
                    }

                    // Services
                    if ($visit->services && is_array($visit->services)) {
                        foreach ($visit->services as $service) {
                            $service = $this->sanitizeString($service);
                            $service = trim($service);
                            if ($service === '') continue;
                            $key = strtolower($service);
                            $serviceCounts[$key] = ($serviceCounts[$key] ?? 0) + 1;
                        }
                    }
                }

                // Medicines dispensed within range
                foreach ($medicineLogs as $log) {
                    $name = $this->sanitizeString($log->medicine->name ?? 'Unknown');
                    $key = strtolower($name);
                    $medicineCounts[$key] = ($medicineCounts[$key] ?? 0) + (int) $log->quantity;
                }

                // Format lists
                $row['complaints'] = $this->sanitizeString($this->formatCountList($complaintCounts));
                $row['medicines'] = $this->sanitizeString($this->formatCountList($medicineCounts));
                $row['services'] = $this->sanitizeString($this->formatCountList($serviceCounts));

                // Row total
                $row['total'] = $row['male'] + $row['female'] + $row['bsis1'] + $row['bsis2'] + $row['bsis3'] + $row['bsis4'] + $row['faculty_admin'] + $row['carmenanon'] + $row['non_carmenanon'];

                $rows[] = $row;

                // Accumulate grand totals
                foreach (['male','female','bsis1','bsis2','bsis3','bsis4','faculty_admin','carmenanon','non_carmenanon','total'] as $key) {
                    $grandTotals[$key] += $row[$key];
                }
            }

            $this->grandTotals = $grandTotals;
            return $rows;
        });
    }

    protected function extractYearLevel(?string $yearSection): ?int
    {
        if (!$yearSection) return null;
        if (preg_match('/\b(\d)/', $yearSection, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }

    protected function formatCountList(array $counts): string
    {
        $parts = [];
        foreach ($counts as $key => $count) {
            $label = $this->sanitizeString(ucwords($key));
            $parts[] = $label . '-' . $count;
        }
        return implode(', ', $parts);
    }

    protected function sanitizeString(?string $value): string
    {
        if ($value === null) return '';
        $value = (string) $value;
        if (!mb_check_encoding($value, 'UTF-8')) {
            $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }
        return $value;
    }

    public function exportPdf()
    {
        $this->computeReport();

        $url = route('reports.clinic-report.pdf', [
            'type' => $this->reportType,
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return redirect()->away($url);
    }

    public function render()
    {
        return view('livewire.reports.clinic-report');
    }
}