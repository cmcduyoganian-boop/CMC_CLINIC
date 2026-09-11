<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\ClinicVisit;
use App\Models\Medicine;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\PendingRegistration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NurseDashboard extends Component
{
    public $dateRange = 'today';
    public $visitType = 'all';
    public $patientType = 'all';
    public ?string $customStartDate = null;
    public ?string $customEndDate = null;
    public $dashboardSearch = '';
    public $showActivitiesModal = false;
    public $autoRefreshInterval = 60000;

    protected $listeners = ['resetFilters'];

    public function mount()
    {
        $this->customStartDate = now()->startOfDay()->format('Y-m-d');
        $this->customEndDate = now()->endOfDay()->format('Y-m-d');
    }

    private function getDateRange(): array
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

    private function cacheKey(string $method): string
    {
        return "dashboard:nurse:{$method}:{$this->dateRange}:{$this->visitType}:{$this->patientType}:{$this->customStartDate}:{$this->customEndDate}";
    }

    private function buildVisitQuery()
    {
        [$start, $end] = $this->getDateRange();

        return ClinicVisit::whereBetween('visit_date', [$start, $end])
            ->when($this->patientType !== 'all', fn ($q) => $q->whereHas('patient', fn ($s) => $s->where('category', $this->patientType)))
            ->when($this->visitType !== 'all', fn ($q) => $q->where('visit_type', $this->visitType));
    }

    #[Computed]
    public function visitsToday(): array
    {
        return Cache::remember($this->cacheKey('visits_today'), 30, function () {
            [$start, $end] = $this->getDateRange();
            $total = $this->buildVisitQuery()->count();

            $periodLength = $start->diffInDays($end) + 1;
            $previousStart = (clone $start)->subDays($periodLength);
            $previousEnd = (clone $start)->subDay()->endOfDay();

            $previous = ClinicVisit::whereBetween('visit_date', [$previousStart, $previousEnd])
                ->when($this->patientType !== 'all', fn ($q) => $q->whereHas('patient', fn ($s) => $s->where('category', $this->patientType)))
                ->when($this->visitType !== 'all', fn ($q) => $q->where('visit_type', $this->visitType))
                ->count();

            $trend = $previous > 0 ? round((($total - $previous) / $previous) * 100) : ($total > 0 ? 100 : 0);

            return ['total' => $total, 'trend' => $trend, 'trendType' => $trend >= 0 ? 'up' : 'down'];
        });
    }

    #[Computed]
    public function lowStockMedicines(): int
    {
        return Cache::remember('dashboard:nurse:low_stock_medicines', 60, function () {
            return Medicine::whereRaw('quantity <= minimum_stock')->where('status', 'active')->count();
        });
    }

    #[Computed]
    public function pendingAppointments(): int
    {
        return Cache::remember($this->cacheKey('pending_appointments'), 30, function () {
            [$start, $end] = $this->getDateRange();
            return Appointment::where('status', 'scheduled')
                ->whereBetween('appointment_date', [$start, $end])
                ->when($this->patientType !== 'all', fn ($q) => $q->whereHas('patient', fn ($s) => $s->where('category', $this->patientType)))
                ->count();
        });
    }

    #[Computed]
    public function abnormalVitals(): int
    {
        return Cache::remember($this->cacheKey('abnormal_vitals'), 30, function () {
            [$start, $end] = $this->getDateRange();
            return ClinicVisit::whereBetween('created_at', [$start, $end])
                ->where(function ($q) {
                    $q->where(fn ($s) => $s->whereNotNull('temperature')->where('temperature', '<', 35.0))
                      ->orWhere(fn ($s) => $s->whereNotNull('temperature')->where('temperature', '>=', 38.0))
                      ->orWhere(fn ($s) => $s->whereNotNull('pulse_rate')->where('pulse_rate', '<', 50))
                      ->orWhere(fn ($s) => $s->whereNotNull('pulse_rate')->where('pulse_rate', '>', 120))
                      ->orWhere(fn ($s) => $s->whereNotNull('respiratory_rate')->where('respiratory_rate', '<', 8))
                      ->orWhere(fn ($s) => $s->whereNotNull('respiratory_rate')->where('respiratory_rate', '>', 30))
                      ->orWhere(fn ($s) => $s->whereNotNull('bp_systolic')->where('bp_systolic', '<', 80))
                      ->orWhere(fn ($s) => $s->whereNotNull('bp_systolic')->where('bp_systolic', '>=', 180))
                      ->orWhere(fn ($s) => $s->whereNotNull('bp_diastolic')->where('bp_diastolic', '<', 50))
                      ->orWhere(fn ($s) => $s->whereNotNull('bp_diastolic')->where('bp_diastolic', '>=', 120))
                      ->orWhere(fn ($s) => $s->whereNotNull('spo2')->where('spo2', '<=', 90));
                })
                ->when($this->patientType !== 'all', fn ($q) => $q->whereHas('patient', fn ($s) => $s->where('category', $this->patientType)))
                ->count();
        });
    }

    #[Computed]
    public function pendingUserApprovals(): int
    {
        return Cache::remember('dashboard:nurse:pending_approvals', 60, function () {
            return User::where('approval_status', 'pending')->where('id', '!=', 1)->count() + PendingRegistration::count();
        });
    }

    #[Computed]
    public function last7DaysChart(): array
    {
        return Cache::remember($this->cacheKey('last_7_days_chart'), 60, function () {
            [$start, $end] = $this->getDateRange();
            $days = $start->diffInDays($end) + 1;

            if ($days > 30) {
                $visits = $this->buildVisitQuery()
                    ->selectRaw('DATE_FORMAT(visit_date, "%Y-%u") as period, COUNT(*) as count')
                    ->groupBy('period')
                    ->orderBy('period')
                    ->pluck('count', 'period');

                return [
                    'labels' => $visits->keys()->map(fn ($p) => 'Week ' . substr($p, -2))->values()->toArray(),
                    'data' => $visits->values()->map(fn ($c) => (int) $c)->toArray(),
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

            return ['labels' => $labels, 'data' => $data];
        });
    }

    #[Computed]
    public function visitsTrendData(): array
    {
        return Cache::remember($this->cacheKey('visits_trend'), 30, function () {
            [$start, $end] = $this->getDateRange();
            $daysDiff = $start->diffInDays($end);

            if ($daysDiff < 6) {
                $start = now()->subDays(6)->startOfDay();
                $end = now()->endOfDay();
                $daysDiff = 6;
            }

            if ($daysDiff > 30) {
                $visits = ClinicVisit::selectRaw('DATE_FORMAT(visit_date, "%Y-%u") as week, COUNT(*) as count')
                    ->whereBetween('visit_date', [$start, $end])
                    ->when($this->patientType !== 'all', fn ($q) => $q->whereHas('patient', fn ($s) => $s->where('category', $this->patientType)))
                    ->when($this->visitType !== 'all', fn ($q) => $q->where('visit_type', $this->visitType))
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get();

                return [
                    'labels' => $visits->map(fn ($v) => 'Week ' . substr($v->week, -2))->toArray(),
                    'data' => $visits->pluck('count')->toArray(),
                ];
            }

            $visits = ClinicVisit::selectRaw('DATE(visit_date) as date, COUNT(*) as count')
                ->whereBetween('visit_date', [$start, $end])
                ->when($this->patientType !== 'all', fn ($q) => $q->whereHas('patient', fn ($s) => $s->where('category', $this->patientType)))
                ->when($this->visitType !== 'all', fn ($q) => $q->where('visit_type', $this->visitType))
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date');

            $labels = [];
            $data = [];
            for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
                $key = $day->format('Y-m-d');
                $labels[] = $day->format('M d');
                $data[] = (int) ($visits[$key] ?? 0);
            }

            return compact('labels', 'data');
        });
    }

    private function normalizeAddress(string $address): string
    {
        $clean = strtolower(trim($address));
        $clean = preg_replace('/[^\w\s]/', ' ', $clean);
        $clean = preg_replace('/\s+/', ' ', $clean);
        $words = array_filter(explode(' ', $clean));
        sort($words);
        return implode(' ', $words);
    }

    #[Computed]
    public function patientLocationData(): array
    {
        return Cache::remember($this->cacheKey('patient_location'), 60, function () {
            [$start, $end] = $this->getDateRange();

            $locations = ClinicVisit::selectRaw('COALESCE(NULLIF(TRIM(p.address), ""), "Address not provided") as address, COUNT(*) as count')
                ->join('patients as p', 'clinic_visits.patient_id', '=', 'p.id')
                ->whereBetween('clinic_visits.visit_date', [$start, $end])
                ->when($this->patientType !== 'all', fn ($q) => $q->where('p.category', $this->patientType))
                ->when($this->visitType !== 'all', fn ($q) => $q->where('clinic_visits.visit_type', $this->visitType))
                ->groupBy('address')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            $ranked = $locations->map(fn ($l) => ['label' => $l->address, 'count' => (int) $l->count])->values()->toArray();

            return [
                'labels' => $locations->pluck('address')->toArray(),
                'data' => $locations->pluck('count')->map(fn ($c) => (int) $c)->toArray(),
                'rankedLocations' => $ranked,
                'topLocation' => $locations->first()?->address ?? 'No location data',
                'topCount' => (int) ($locations->first()?->count ?? 0),
            ];
        });
    }

    #[Computed]
    public function vitalSignsOverview(): array
    {
        return Cache::remember($this->cacheKey('vital_signs'), 30, function () {
            [$start, $end] = $this->getDateRange();

            $query = ClinicVisit::whereBetween('created_at', [$start, $end])
                ->when($this->patientType !== 'all', fn ($q) => $q->whereHas('patient', fn ($s) => $s->where('category', $this->patientType)));

            $total = $query->count();

            if ($total === 0) {
                return ['normal' => 0, 'elevated' => 0, 'abnormal' => 0, 'total' => 0];
            }

            $abnormal = (clone $query)->where(function ($q) {
                $q->where('temperature', '<', 35.0)
                  ->orWhere('temperature', '>=', 38.0)
                  ->orWhere('pulse_rate', '<', 50)
                  ->orWhere('pulse_rate', '>', 120)
                  ->orWhere('respiratory_rate', '<', 8)
                  ->orWhere('respiratory_rate', '>', 30)
                  ->orWhere('bp_systolic', '<', 80)
                  ->orWhere('bp_systolic', '>=', 180)
                  ->orWhere('bp_diastolic', '<', 50)
                  ->orWhere('bp_diastolic', '>=', 120)
                  ->orWhere('spo2', '<=', 90);
            })->count();

            $elevated = (clone $query)->where(function ($q) {
                $q->whereBetween('temperature', [37.1, 37.9])
                  ->orWhereBetween('pulse_rate', [101, 120])
                  ->orWhereBetween('respiratory_rate', [21, 30])
                  ->orWhereBetween('bp_systolic', [130, 139])
                  ->orWhereBetween('bp_systolic', [90, 89]) // impossible, so skip
                  ->orWhereBetween('bp_diastolic', [80, 89])
                  ->orWhereBetween('spo2', [91, 92]);
            })->count();

            $normal = $total - $abnormal - $elevated;

            return [
                'normal' => max(0, $normal),
                'elevated' => $elevated,
                'abnormal' => $abnormal,
                'total' => $total,
            ];
        });
    }

    #[Computed]
    public function medicineInventory(): array
    {
        return Cache::remember('dashboard:nurse:medicine_inventory', 60, function () {
            $base = Medicine::where('status', 'active');
            return [
                'total' => (clone $base)->count(),
                'available' => (clone $base)->whereRaw('quantity > minimum_stock')->count(),
                'lowStock' => (clone $base)->whereRaw('quantity <= minimum_stock')->count(),
                'expiringSoon' => (clone $base)
                    ->whereNotNull('expiration_date')
                    ->whereDate('expiration_date', '>=', now())
                    ->whereDate('expiration_date', '<=', now()->addDays(30))
                    ->count(),
            ];
        });
    }

    #[Computed]
    public function appointmentStats(): array
    {
        return Cache::remember($this->cacheKey('appointment_stats'), 30, function () {
            [$start, $end] = $this->getDateRange();
            $query = Appointment::whereBetween('appointment_date', [$start, $end])
                ->when($this->patientType !== 'all', fn ($q) => $q->whereHas('patient', fn ($s) => $s->where('category', $this->patientType)));

            return [
                'total' => (clone $query)->count(),
                'scheduled' => (clone $query)->where('status', 'scheduled')->count(),
                'completed' => (clone $query)->where('status', 'completed')->count(),
                'noShow' => (clone $query)->where('status', 'no-show')->count(),
                'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            ];
        });
    }

    #[Computed]
    public function recentActivities(): array
    {
        return Cache::remember($this->cacheKey('recent_activities'), 30, function () {
            [$start, $end] = $this->getDateRange();

            $activities = [];

            $visits = ClinicVisit::whereBetween('created_at', [$start, $end])
                ->with('patient:id,name')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(fn ($v) => [
                    'type' => 'visit', 'icon' => 'fa-stethoscope', 'color' => 'blue',
                    'message' => ($v->patient->name ?? 'Patient') . ' - New Clinic Visit Recorded',
                    'timestamp' => $v->created_at,
                ]);

            $appointments = Appointment::whereBetween('created_at', [$start, $end])
                ->with('patient:id,name')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(fn ($a) => [
                    'type' => 'appointment', 'icon' => 'fa-calendar-check', 'color' => 'green',
                    'message' => ($a->patient->name ?? 'Patient') . ' - Follow-up Appointment Scheduled',
                    'timestamp' => $a->created_at,
                ]);

            $lowStock = Medicine::whereRaw('quantity <= minimum_stock')
                ->orderBy('updated_at', 'desc')
                ->limit(2)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'inventory', 'icon' => 'fa-exclamation-triangle', 'color' => 'orange',
                    'message' => $m->name . ' - Low Stock Alert',
                    'timestamp' => $m->updated_at,
                ]);

            $expiringSoon = Medicine::where('status', 'active')
                ->whereNotNull('expiration_date')
                ->whereDate('expiration_date', '>=', now())
                ->whereDate('expiration_date', '<=', now()->addDays(30))
                ->orderBy('expiration_date')
                ->limit(2)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'inventory', 'icon' => 'fa-hourglass-half', 'color' => 'orange',
                    'message' => $m->name . ' - Expiring on ' . $m->expiration_date->format('M d, Y'),
                    'timestamp' => $m->updated_at,
                ]);

            $pendingUsers = User::where('approval_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(2)
                ->get()
                ->map(fn ($u) => [
                    'type' => 'user', 'icon' => 'fa-user-clock', 'color' => 'blue',
                    'message' => $u->name . ' - Pending Account Approval',
                    'timestamp' => $u->created_at,
                ]);

            return collect()
                ->merge($visits)
                ->merge($appointments)
                ->merge($lowStock)
                ->merge($expiringSoon)
                ->merge($pendingUsers)
                ->sortByDesc('timestamp')
                ->take(10)
                ->values()
                ->toArray();
        });
    }

    #[Computed]
    public function allActivities(): array
    {
        if (!$this->showActivitiesModal) {
            return [];
        }

        return Cache::remember('dashboard:nurse:all_activities', 60, function () {
            $activities = [];

            $visits = ClinicVisit::with('patient:id,name')
                ->orderBy('created_at', 'desc')
                ->limit(30)
                ->get()
                ->map(fn ($v) => [
                    'type' => 'visit', 'icon' => 'fa-stethoscope', 'color' => 'blue',
                    'message' => ($v->patient->name ?? 'Patient') . ' — Clinic Visit Recorded',
                    'detail' => $v->visit_type ? ucfirst(str_replace('_', ' ', $v->visit_type)) : null,
                    'timestamp' => $v->created_at, 'link' => route('clinic-visit.index'),
                ]);

            $appointments = Appointment::with('patient:id,name')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(fn ($a) => [
                    'type' => 'appointment', 'icon' => 'fa-calendar-check', 'color' => 'green',
                    'message' => ($a->patient->name ?? 'Patient') . ' — Appointment ' . ucfirst($a->status ?? 'Scheduled'),
                    'detail' => $a->appointment_date ? Carbon::parse($a->appointment_date)->format('M d, Y') : null,
                    'timestamp' => $a->created_at, 'link' => route('appointments.index'),
                ]);

            $lowStock = Medicine::whereRaw('quantity <= minimum_stock')
                ->where('status', 'active')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'inventory', 'icon' => 'fa-exclamation-triangle', 'color' => 'orange',
                    'message' => $m->name . ' — Low Stock Alert',
                    'detail' => 'Qty: ' . $m->quantity . ' (Min: ' . $m->minimum_stock . ')',
                    'timestamp' => $m->updated_at, 'link' => route('medicines.index'),
                ]);

            $expiringSoon = Medicine::where('status', 'active')
                ->whereNotNull('expiration_date')
                ->whereDate('expiration_date', '>=', now())
                ->whereDate('expiration_date', '<=', now()->addDays(30))
                ->orderBy('expiration_date')
                ->limit(10)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'inventory', 'icon' => 'fa-hourglass-half', 'color' => 'orange',
                    'message' => $m->name . ' — Expiring Soon',
                    'detail' => 'Expires: ' . $m->expiration_date->format('M d, Y'),
                    'timestamp' => $m->updated_at, 'link' => route('medicines.index'),
                ]);

            $pendingUsers = User::where('approval_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(fn ($u) => [
                    'type' => 'user', 'icon' => 'fa-user-clock', 'color' => 'purple',
                    'message' => $u->name . ' — Pending Account Approval',
                    'detail' => $u->email ?? null,
                    'timestamp' => $u->created_at, 'link' => route('admin.users'),
                ]);

            return collect()
                ->merge($visits)
                ->merge($appointments)
                ->merge($lowStock)
                ->merge($expiringSoon)
                ->merge($pendingUsers)
                ->sortByDesc('timestamp')
                ->take(50)
                ->values()
                ->toArray();
        });
    }

    #[Computed]
    public function dashboardSearchResults(): array
    {
        $term = trim($this->dashboardSearch);
        if (strlen($term) < 2) {
            return [];
        }
        return Patient::whereHas('clinicVisits')
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('year_section', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->map(fn ($p) => [
                'name' => $p->name,
                'category' => ucfirst($p->category ?? 'Patient'),
                'visitId' => $p->clinicVisits()->latest('visit_date')->first()?->id,
            ])
            ->toArray();
    }

    public function openActivitiesModal(): void { $this->showActivitiesModal = true; }
    public function closeActivitiesModal(): void { $this->showActivitiesModal = false; }

    public function render()
    {
        $data = [
            'visitsToday' => $this->visitsToday,
            'lowStockMedicines' => $this->lowStockMedicines,
            'pendingAppointments' => $this->pendingAppointments,
            'abnormalVitals' => $this->abnormalVitals,
            'pendingUserApprovals' => $this->pendingUserApprovals,
            'visitsTrendData' => $this->visitsTrendData,
            'patientLocationData' => $this->patientLocationData,
            'last7DaysChart' => $this->last7DaysChart,
            'vitalSignsOverview' => $this->vitalSignsOverview,
            'medicineInventory' => $this->medicineInventory,
            'appointmentStats' => $this->appointmentStats,
            'recentActivities' => $this->recentActivities,
            'allActivities' => $this->allActivities,
            'dashboardSearchResults' => $this->dashboardSearchResults,
        ];

        $this->dispatch('dashboard-charts-update', chartData: [
            'visits' => $data['last7DaysChart'],
            'trend' => $data['visitsTrendData'],
            'location' => $data['patientLocationData'],
            'vitals' => $data['vitalSignsOverview'],
            'appointments' => $data['appointmentStats'],
            'medicine' => $data['medicineInventory'],
        ]);

        return view('livewire.dashboard.nurse-dashboard', $data);
    }

    public function resetFilters(): void
    {
        $this->dateRange = 'today';
        $this->visitType = 'all';
        $this->patientType = 'all';
        $this->customStartDate = now()->startOfDay()->format('Y-m-d');
        $this->customEndDate = now()->endOfDay()->format('Y-m-d');
    }
}