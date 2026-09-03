<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\ClinicVisit;
use App\Models\Medicine;
use App\Models\Patient;
use Carbon\Carbon;

class ClinicStaffDashboard extends Component
{
    // ============ FILTER PROPERTIES ============
    public $dateRange = 'last_30';
    public $patientType = 'all';
    public $customStartDate = null;
    public $customEndDate = null;

    // ============ AUTO-REFRESH ============
    public $autoRefreshInterval = 30000; // 30 seconds

    protected $listeners = ['resetFilters'];

    public function mount()
    {
        $this->customStartDate = now()->startOfDay()->format('Y-m-d');
        $this->customEndDate = now()->endOfDay()->format('Y-m-d');
    }

    // ============ GET DATE RANGE ============
    private function getDateRange()
    {
        $start = now()->startOfDay();
        $end = now()->endOfDay();

        switch ($this->dateRange) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'yesterday':
                $start = now()->subDay()->startOfDay();
                $end = now()->subDay()->endOfDay();
                break;
            case 'last_7':
                $start = now()->subDays(7)->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'last_30':
                $start = now()->subDays(30)->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'this_month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                break;
            case 'custom':
                if ($this->customStartDate && $this->customEndDate) {
                    $start = Carbon::createFromFormat('Y-m-d', $this->customStartDate)->startOfDay();
                    $end = Carbon::createFromFormat('Y-m-d', $this->customEndDate)->endOfDay();
                }
                break;
        }

        return [$start, $end];
    }

    // ============ BUILD BASE QUERY ============
    private function buildVisitQuery()
    {
        [$start, $end] = $this->getDateRange();

        $query = ClinicVisit::whereBetween('visit_date', [$start, $end]);

        if ($this->patientType !== 'all') {
            $query->whereHas('patient', function ($q) {
                $q->where('category', $this->patientType);
            });
        }

        return $query;
    }

    // ============ KPI: VISITS WITH TREND ============
    public function getVisitsToday()
    {
        [$start, $end] = $this->getDateRange();
        $total = $this->buildVisitQuery()->count();

        // Calculate trend (compare with previous period)
        $periodLength = $start->diffInDays($end) + 1;
        $previousStart = (clone $start)->subDays($periodLength);
        $previousEnd = (clone $start)->subDay()->endOfDay();

        $previousQuery = ClinicVisit::whereBetween('visit_date', [$previousStart, $previousEnd]);

        if ($this->patientType !== 'all') {
            $previousQuery->whereHas('patient', function ($q) {
                $q->where('category', $this->patientType);
            });
        }

        $previous = $previousQuery->count();

        $trend = $previous > 0 ? round((($total - $previous) / $previous) * 100) : ($total > 0 ? 100 : 0);

        return [
            'total' => $total,
            'trend' => $trend,
            'trendType' => $trend >= 0 ? 'up' : 'down',
        ];
    }

    // ============ KPI: LOW STOCK MEDICINES (VIEW ONLY) ============
    public function getLowStockMedicines()
    {
        return Medicine::whereRaw('quantity <= minimum_stock')
            ->where('status', 'active')
            ->count();
    }

    // ============ KPI: TOTAL PATIENTS ============
    public function getTotalPatients()
    {
        return Patient::count();
    }

    // ============ CHART DATA: LAST 7 DAYS (fixed window) ============
    public function getLast7DaysChart()
    {
        $start = now()->subDays(6)->startOfDay();
        $end = now()->endOfDay();

        $visits = ClinicVisit::selectRaw('DATE(visit_date) as date, COUNT(*) as count')
            ->whereBetween('visit_date', [$start, $end])
            ->groupBy('date')
            ->pluck('count', 'date');

        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('D');
            $data[] = (int) ($visits[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    // ============ CHART DATA: VISITS TREND (respects filters) ============
    public function getVisitsTrendData()
    {
        [$start, $end] = $this->getDateRange();

        $visits = ClinicVisit::selectRaw('DATE(visit_date) as date, COUNT(*) as count')
            ->whereBetween('visit_date', [$start, $end])
            ->when($this->patientType !== 'all', function ($q) {
                return $q->whereHas('patient', function ($subQ) {
                    $subQ->where('category', $this->patientType);
                });
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $visits->map(fn($v) => Carbon::parse($v->date)->format('M d'))->toArray();

        return [
            'labels' => $labels,
            'data' => $visits->pluck('count')->toArray(),
        ];
    }

    public function getPatientLocationData()
    {
        $locations = $this->buildVisitQuery()
            ->with('patient:id,address')
            ->get()
            ->groupBy(function ($visit) {
                $address = trim((string) ($visit->patient?->address ?? ''));

                return $address !== '' ? $address : 'Address not provided';
            })
            ->map(fn ($visits) => $visits->count())
            ->sortDesc()
            ->take(10);

        return [
            'labels' => $locations->keys()->values()->toArray(),
            'data' => $locations->values()->map(fn ($count) => (int) $count)->toArray(),
            'topLocation' => $locations->keys()->first() ?? 'No location data',
            'topCount' => (int) ($locations->first() ?? 0),
        ];
    }

    // ============ VITAL SIGNS OVERVIEW (assists with recording visits) ============
    public function getVitalSignsOverview()
    {
        [$start, $end] = $this->getDateRange();

        $query = ClinicVisit::whereBetween('created_at', [$start, $end]);

        if ($this->patientType !== 'all') {
            $query->whereHas('patient', function ($q) {
                $q->where('category', $this->patientType);
            });
        }

        $total = $query->count();

        if ($total === 0) {
            return [
                'normal' => 0,
                'elevated' => 0,
                'abnormal' => 0,
                'total' => 0,
            ];
        }

        $abnormal = (clone $query)->where(function ($q) {
            $q->where('temperature', '<', 36)
              ->orWhere('temperature', '>', 38)
              ->orWhere('spo2', '<', 95)
              ->orWhere('bp_systolic', '>', 140)
              ->orWhere('bp_systolic', '<', 90)
              ->orWhere('bp_diastolic', '>', 90)
              ->orWhere('bp_diastolic', '<', 60)
              ->orWhere('pulse_rate', '>', 100)
              ->orWhere('pulse_rate', '<', 60)
              ->orWhere('respiratory_rate', '>', 20)
              ->orWhere('respiratory_rate', '<', 12);
        })->count();

        $elevated = (clone $query)->where(function ($q) {
            $q->whereBetween('temperature', [37.1, 38])
              ->orWhereBetween('spo2', [95, 98])
              ->orWhereBetween('bp_systolic', [130, 140])
              ->orWhereBetween('bp_diastolic', [80, 90])
              ->orWhereBetween('pulse_rate', [90, 100])
              ->orWhereBetween('respiratory_rate', [18, 20]);
        })->count();

        $normal = $total - $abnormal - $elevated;

        return [
            'normal' => $normal,
            'elevated' => $elevated,
            'abnormal' => $abnormal,
            'total' => $total,
        ];
    }

    // ============ MEDICINE INVENTORY STATUS (VIEW ONLY) ============
    public function getMedicineInventoryStatus()
    {
        $total = Medicine::where('status', 'active')->count();
        $available = Medicine::where('status', 'active')->whereRaw('quantity > minimum_stock')->count();
        $lowStock = Medicine::where('status', 'active')->whereRaw('quantity <= minimum_stock')->count();
        $expiringSoon = Medicine::where('status', 'active')
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '>=', now())
            ->whereDate('expiration_date', '<=', now()->addDays(30))
            ->count();

        return [
            'total' => $total,
            'available' => $available,
            'lowStock' => $lowStock,
            'expiringSoon' => $expiringSoon,
        ];
    }

    // ============ RECENT ACTIVITIES (visits recorded & inventory alerts) ============
    public function getRecentActivities()
    {
        [$start, $end] = $this->getDateRange();

        $activities = [];

        // Recent clinic visits recorded (what clinic staff assists with)
        $visits = ClinicVisit::whereBetween('created_at', [$start, $end])
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($visit) {
                return [
                    'type' => 'visit',
                    'icon' => 'fa-stethoscope',
                    'color' => 'blue',
                    'message' => ($visit->patient->name ?? 'Patient') . ' - New Clinic Visit Recorded',
                    'timestamp' => $visit->created_at,
                ];
            });

        $activities = array_merge($activities, $visits->toArray());

        // Low stock alerts (view only)
        $lowStock = Medicine::whereRaw('quantity <= minimum_stock')
            ->orderBy('updated_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function ($med) {
                return [
                    'type' => 'inventory',
                    'icon' => 'fa-exclamation-triangle',
                    'color' => 'orange',
                    'message' => $med->name . ' - Low Stock Alert',
                    'timestamp' => $med->updated_at,
                ];
            });

        $activities = array_merge($activities, $lowStock->toArray());

        // Expiring soon alerts (view only)
        $expiringSoon = Medicine::where('status', 'active')
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '>=', now())
            ->whereDate('expiration_date', '<=', now()->addDays(30))
            ->orderBy('expiration_date')
            ->limit(2)
            ->get()
            ->map(function ($med) {
                return [
                    'type' => 'inventory',
                    'icon' => 'fa-hourglass-half',
                    'color' => 'orange',
                    'message' => $med->name . ' - Expiring on ' . $med->expiration_date->format('M d, Y'),
                    'timestamp' => $med->updated_at,
                ];
            });

        $activities = array_merge($activities, $expiringSoon->toArray());

        // Sort by timestamp and limit to 10
        usort($activities, function ($a, $b) {
            return $b['timestamp']->timestamp <=> $a['timestamp']->timestamp;
        });

        return array_slice($activities, 0, 10);
    }

    public function render()
    {
        return view('livewire.dashboard.clinic-staff-dashboard', [
            'visitsToday' => $this->getVisitsToday(),
            'lowStockMedicines' => $this->getLowStockMedicines(),
            'totalPatients' => $this->getTotalPatients(),
            'vitalSignsOverview' => $this->getVitalSignsOverview(),
            'medicineInventory' => $this->getMedicineInventoryStatus(),
            'last7DaysChart' => $this->getLast7DaysChart(),
            'visitsTrendData' => $this->getVisitsTrendData(),
            'patientLocationData' => $this->getPatientLocationData(),
            'recentActivities' => $this->getRecentActivities(),
        ]);
    }

    public function resetFilters()
    {
        $this->dateRange = 'this_month';
        $this->patientType = 'all';
    }
}