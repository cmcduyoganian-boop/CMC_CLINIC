<?php

namespace App\Livewire\Reports;

use App\Models\ClinicVisit;
use App\Models\MedicineInventoryLog;
use App\Models\Medicine;
use Livewire\Component;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function computeReport(): void
    {
        try {
            $start = Carbon::createFromFormat('Y-m-d', $this->startDate)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
        } catch (\Exception $e) {
            return;
        }

        $this->reportRows = [];
        $this->grandTotals = [
            'male' => 0, 'female' => 0,
            'bsis1' => 0, 'bsis2' => 0, 'bsis3' => 0, 'bsis4' => 0,
            'faculty_admin' => 0,
            'carmenanon' => 0, 'non_carmenanon' => 0,
            'total' => 0,
        ];

        $ranges = $this->getDateRanges();

        foreach ($ranges as $range) {
            $visits = ClinicVisit::with('patient')
                ->whereDate('visit_date', '>=', $range['start']->toDateString())
                ->whereDate('visit_date', '<=', $range['end']->toDateString())
                ->get();

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
            $medicineLogs = MedicineInventoryLog::whereBetween('created_at', [$range['start'], $range['end']])
                ->where('action', 'used')
                ->with('medicine')
                ->get();

            foreach ($medicineLogs as $log) {
                $name = $this->sanitizeString($log->medicine->name ?? 'Unknown');
                $key = strtolower($name);
                $medicineCounts[$key] = ($medicineCounts[$key] ?? 0) + (int) $log->quantity;
            }

            // Format lists
            $row['complaints'] = $this->sanitizeString($this->formatCountList($complaintCounts));
            $row['medicines'] = $this->sanitizeString($this->formatCountList($medicineCounts));
            $row['services'] = $this->sanitizeString($this->formatCountList($serviceCounts));

            // Row total (sum of numeric columns)
            $row['total'] = $row['male'] + $row['female'] + $row['bsis1'] + $row['bsis2'] + $row['bsis3'] + $row['bsis4'] + $row['faculty_admin'] + $row['carmenanon'] + $row['non_carmenanon'];

            $this->reportRows[] = $row;

            // Accumulate grand totals
            foreach (['male','female','bsis1','bsis2','bsis3','bsis4','faculty_admin','carmenanon','non_carmenanon','total'] as $key) {
                $this->grandTotals[$key] += $row[$key];
            }
        }
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

        $pdf = Pdf::loadView('pdf.clinic-report', [
            'reportType' => $this->reportType,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'reportRows' => $this->reportRows,
            'grandTotals' => $this->grandTotals,
        ]);

        return $pdf->download('clinic-report-' . $this->reportType . '-' . now()->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        return view('livewire.reports.clinic-report');
    }
}
