<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\ClinicVisit;
use App\Models\Medicine;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\PendingRegistration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NurseDashboard extends Component
{
    // ============ FILTER PROPERTIES ============
    public $dateRange = 'last_30';
    public $visitType = 'all';
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

        if ($this->visitType !== 'all') {
            $query->where('visit_type', $this->visitType);
        }

        return $query;
    }

    // ============ KPI: VISITS TODAY WITH TREND ============
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

        if ($this->visitType !== 'all') {
            $previousQuery->where('visit_type', $this->visitType);
        }
        
        $previous = $previousQuery->count();
        
        // Calculate percentage trend
        $trend = $previous > 0 ? round((($total - $previous) / $previous) * 100) : ($total > 0 ? 100 : 0);

        return [
            'total' => $total,
            'trend' => $trend,
            'trendType' => $trend >= 0 ? 'up' : 'down',
        ];
    }

    // ============ KPI: LOW STOCK MEDICINES ============
    public function getLowStockMedicines()
    {
        return Medicine::whereRaw('quantity <= minimum_stock')
            ->where('status', 'active')
            ->count();
    }

    // ============ KPI: PENDING APPOINTMENTS ============
    public function getPendingAppointments()
    {
        [$start, $end] = $this->getDateRange();

        $query = Appointment::where('status', 'scheduled')
            ->whereBetween('appointment_date', [$start, $end]);

        if ($this->patientType !== 'all') {
            $query->whereHas('patient', function ($q) {
                $q->where('category', $this->patientType);
            });
        }

        return $query->count();
    }

    // ============ KPI: ABNORMAL VITALS ============
    public function getAbnormalVitals()
    {
        [$start, $end] = $this->getDateRange();

        $query = ClinicVisit::whereBetween('created_at', [$start, $end])
            ->where(function ($q) {
                $q->where('temperature', '<', 36)
                  ->orWhere('temperature', '>', 38)
                  ->orWhere('spo2', '<', 95);
            });

        if ($this->patientType !== 'all') {
            $query->whereHas('patient', function ($q) {
                $q->where('category', $this->patientType);
            });
        }

        return $query->count();
    }

    // ============ KPI: PENDING USER APPROVALS ============
    public function getPendingUserApprovals()
    {
        return User::where('approval_status', 'pending')
            ->where('id', '!=', 1)
            ->count() + PendingRegistration::count();
    }

    // ============ CHART DATA: LAST 7 DAYS (fixed window, independent of filters) ============
    public function getLast7DaysChart()
    {
        [$start, $end] = $this->getDateRange();
        $days = $start->diffInDays($end) + 1;

        if ($days > 30) {
            $visits = $this->buildVisitQuery()
                ->selectRaw((DB::connection()->getDriverName() === 'sqlite'
                    ? "strftime('%Y-%W', visit_date)"
                    : 'DATE_FORMAT(visit_date, "%Y-%u")') . ' as period, COUNT(*) as count')
                ->groupBy('period')
                ->orderBy('period')
                ->pluck('count', 'period');

            return [
                'labels' => $visits->keys()->map(fn ($period) => 'Week ' . substr($period, -2))->values()->toArray(),
                'data' => $visits->values()->map(fn ($count) => (int) $count)->toArray(),
            ];
        }

        $visits = $this->buildVisitQuery()
            ->selectRaw('DATE(visit_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $labels = [];
        $data = [];

        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $key = $day->format('Y-m-d');
            $labels[] = $days <= 7 ? $day->format('D') : $day->format('M d');
            $data[] = (int) ($visits[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    // ============ CHART DATA: VISITS TREND ============
    public function getVisitsTrendData()
    {
        [$start, $end] = $this->getDateRange();
        
        $daysDiff = $start->diffInDays($end);

        if ($daysDiff > 30) {
            // Weekly data
            $weekExpression = DB::connection()->getDriverName() === 'sqlite'
                ? "strftime('%Y-%W', visit_date)"
                : 'DATE_FORMAT(visit_date, "%Y-%u")';

            $visits = ClinicVisit::selectRaw("{$weekExpression} as week, COUNT(*) as count")
                ->whereBetween('visit_date', [$start, $end])
                ->when($this->patientType !== 'all', function ($q) {
                    return $q->whereHas('patient', function ($subQ) {
                        $subQ->where('category', $this->patientType);
                    });
                })
                ->when($this->visitType !== 'all', fn ($q) => $q->where('visit_type', $this->visitType))
                ->groupBy('week')
                ->orderBy('week')
                ->get();

            $labels = $visits->map(fn($v) => 'Week ' . substr($v->week, -2))->toArray();
        } else {
            // Daily data
            $visits = ClinicVisit::selectRaw('DATE(visit_date) as date, COUNT(*) as count')
                ->whereBetween('visit_date', [$start, $end])
                ->when($this->patientType !== 'all', function ($q) {
                    return $q->whereHas('patient', function ($subQ) {
                        $subQ->where('category', $this->patientType);
                    });
                })
                ->when($this->visitType !== 'all', fn ($q) => $q->where('visit_type', $this->visitType))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $labels = $visits->map(fn($v) => Carbon::parse($v->date)->format('M d'))->toArray();
        }

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

    // ============ HEALTH MONITORING: VITAL SIGNS OVERVIEW ============
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

        $abnormal = $query->where(function ($q) {
            $q->where('temperature', '<', 36)
              ->orWhere('temperature', '>', 38)
              ->orWhere('spo2', '<', 95);
        })->count();

        $elevated = $query->where(function ($q) {
            $q->whereBetween('temperature', [37.1, 38])
              ->orWhereBetween('spo2', [95, 98]);
        })->count();

        $normal = $total - $abnormal - $elevated;

        return [
            'normal' => $normal,
            'elevated' => $elevated,
            'abnormal' => $abnormal,
            'total' => $total,
        ];
    }

    // ============ MEDICINE INVENTORY STATUS ============
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

    // ============ APPOINTMENTS & FOLLOW-UPS ============
    public function getAppointmentStats()
    {
        [$start, $end] = $this->getDateRange();

        $query = Appointment::whereBetween('appointment_date', [$start, $end]);

        if ($this->patientType !== 'all') {
            $query->whereHas('patient', function ($q) {
                $q->where('category', $this->patientType);
            });
        }

        return [
            'total' => $query->count(),
            'scheduled' => $query->where('status', 'scheduled')->count(),
            'completed' => $query->where('status', 'completed')->count(),
            'noShow' => $query->where('status', 'no-show')->count(),
            'cancelled' => $query->where('status', 'cancelled')->count(),
        ];
    }

    // ============ RECENT ACTIVITIES ============
    public function getRecentActivities()
    {
        [$start, $end] = $this->getDateRange();

        $activities = [];

        // Recent clinic visits
        $visits = ClinicVisit::whereBetween('created_at', [$start, $end])
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->limit(5)
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

        // Recent appointments scheduled
        $appointments = Appointment::whereBetween('created_at', [$start, $end])
            ->with('patient')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($apt) {
                return [
                    'type' => 'appointment',
                    'icon' => 'fa-calendar-check',
                    'color' => 'green',
                    'message' => ($apt->patient->name ?? 'Patient') . ' - Follow-up Appointment Scheduled',
                    'timestamp' => $apt->created_at,
                ];
            });

        $activities = array_merge($activities, $appointments->toArray());

        // Low stock alerts
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

        // Expiring soon alerts
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

        // Pending user approvals
        $pendingUsers = User::where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'icon' => 'fa-user-clock',
                    'color' => 'blue',
                    'message' => $user->name . ' - Pending Account Approval',
                    'timestamp' => $user->created_at,
                ];
            });

        $activities = array_merge($activities, $pendingUsers->toArray());

        // Sort by timestamp and limit to 10
        usort($activities, function ($a, $b) {
            return $b['timestamp']->timestamp <=> $a['timestamp']->timestamp;
        });

        return array_slice($activities, 0, 10);
    }

    public function render()
    {
        return view('livewire.dashboard.nurse-dashboard', [
            'visitsToday' => $this->getVisitsToday(),
            'lowStockMedicines' => $this->getLowStockMedicines(),
            'pendingAppointments' => $this->getPendingAppointments(),
            'abnormalVitals' => $this->getAbnormalVitals(),
            'pendingUserApprovals' => $this->getPendingUserApprovals(),
            'visitsTrendData' => $this->getVisitsTrendData(),
            'patientLocationData' => $this->getPatientLocationData(),
            'last7DaysChart' => $this->getLast7DaysChart(),
            'vitalSignsOverview' => $this->getVitalSignsOverview(),
            'medicineInventory' => $this->getMedicineInventoryStatus(),
            'appointmentStats' => $this->getAppointmentStats(),
            'recentActivities' => $this->getRecentActivities(),
        ]);
    }

    public function resetFilters()
    {
        $this->dateRange = 'this_month';
        $this->visitType = 'all';
        $this->patientType = 'all';
    }
}