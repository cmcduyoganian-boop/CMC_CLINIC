<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\ClinicVisit;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClinicStaffDashboard extends Component
{
    public $dateRange = 'today';
    public $patientType = 'all';
    public ?string $customStartDate = null;
    public ?string $customEndDate = null;
    public $dashboardSearch = '';
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
        return "dashboard:clinic_staff:{$method}:{$this->dateRange}:{$this->patientType}:{$this->customStartDate}:{$this->customEndDate}";
    }

    private function buildVisitQuery()
    {
        [$start, $end] = $this->getDateRange();

        return ClinicVisit::whereBetween('visit_date', [$start, $end])
            ->when($this->patientType !== 'all', function ($q) {
                $q->whereHas('patient', fn ($sub) => $sub->where('category', $this->patientType));
            });
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
                ->when($this->patientType !== 'all', function ($q) {
                    $q->whereHas('patient', fn ($sub) => $sub->where('category', $this->patientType));
                })
                ->count();

            $trend = $previous > 0 ? round((($total - $previous) / $previous) * 100) : ($total > 0 ? 100 : 0);

            return [
                'total' => $total,
                'trend' => $trend,
                'trendType' => $trend >= 0 ? 'up' : 'down',
            ];
        });
    }

    #[Computed]
    public function lowStockMedicines(): int
    {
        return Cache::remember('dashboard:clinic_staff:low_stock_medicines', 60, function () {
            return Medicine::whereRaw('quantity <= minimum_stock')
                ->where('status', 'active')
                ->count();
        });
    }

    #[Computed]
    public function totalPatients(): int
    {
        return Cache::remember('dashboard:clinic_staff:total_patients', 300, fn () => Patient::count());
    }

    #[Computed]
    public function last7DaysChart(): array
    {
        return Cache::remember('dashboard:clinic_staff:last_7_days_chart', 60, function () {
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

            return ['labels' => $labels, 'data' => $data];
        });
    }

    #[Computed]
    public function visitsTrendData(): array
    {
        return Cache::remember($this->cacheKey('visits_trend'), 30, function () {
            [$start, $end] = $this->getDateRange();

            $visits = ClinicVisit::selectRaw('DATE(visit_date) as date, COUNT(*) as count')
                ->whereBetween('visit_date', [$start, $end])
                ->when($this->patientType !== 'all', function ($q) {
                    $q->whereHas('patient', fn ($sub) => $sub->where('category', $this->patientType));
                })
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return [
                'labels' => $visits->map(fn ($v) => Carbon::parse($v->date)->format('M d'))->toArray(),
                'data' => $visits->pluck('count')->toArray(),
            ];
        });
    }

    #[Computed]
    public function patientLocationData(): array
    {
        return Cache::remember($this->cacheKey('patient_location'), 60, function () {
            [$start, $end] = $this->getDateRange();

            $locations = ClinicVisit::selectRaw('COALESCE(NULLIF(TRIM(p.address), ""), "Address not provided") as address, COUNT(*) as count')
                ->join('patients as p', 'clinic_visits.patient_id', '=', 'p.id')
                ->whereBetween('clinic_visits.visit_date', [$start, $end])
                ->when($this->patientType !== 'all', function ($q) {
                    $q->where('p.category', $this->patientType);
                })
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
                ->when($this->patientType !== 'all', function ($q) {
                    $q->whereHas('patient', fn ($sub) => $sub->where('category', $this->patientType));
                });

            $total = $query->count();

            if ($total === 0) {
                return ['normal' => 0, 'elevated' => 0, 'abnormal' => 0, 'total' => 0];
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

            return [
                'normal' => $total - $abnormal - $elevated,
                'elevated' => $elevated,
                'abnormal' => $abnormal,
                'total' => $total,
            ];
        });
    }

    #[Computed]
    public function medicineInventory(): array
    {
        return Cache::remember('dashboard:clinic_staff:medicine_inventory', 60, function () {
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
    public function recentActivities(): array
    {
        return Cache::remember($this->cacheKey('recent_activities'), 30, function () {
            [$start, $end] = $this->getDateRange();

            $activities = [];

            $visits = ClinicVisit::whereBetween('created_at', [$start, $end])
                ->with('patient:id,name')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get()
                ->map(fn ($v) => [
                    'type' => 'visit',
                    'icon' => 'fa-stethoscope',
                    'color' => 'blue',
                    'message' => ($v->patient->name ?? 'Patient') . ' - New Clinic Visit Recorded',
                    'timestamp' => $v->created_at,
                ]);

            $lowStock = Medicine::whereRaw('quantity <= minimum_stock')
                ->orderBy('updated_at', 'desc')
                ->limit(2)
                ->get()
                ->map(fn ($m) => [
                    'type' => 'inventory',
                    'icon' => 'fa-exclamation-triangle',
                    'color' => 'orange',
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
                    'type' => 'inventory',
                    'icon' => 'fa-hourglass-half',
                    'color' => 'orange',
                    'message' => $m->name . ' - Expiring on ' . $m->expiration_date->format('M d, Y'),
                    'timestamp' => $m->updated_at,
                ]);

            $all = collect()
                ->merge($visits)
                ->merge($lowStock)
                ->merge($expiringSoon)
                ->sortByDesc('timestamp')
                ->take(10)
                ->values()
                ->toArray();

            return $all;
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

    public function render()
    {
        $data = [
            'visitsToday' => $this->visitsToday,
            'lowStockMedicines' => $this->lowStockMedicines,
            'totalPatients' => $this->totalPatients,
            'vitalSignsOverview' => $this->vitalSignsOverview,
            'medicineInventory' => $this->medicineInventory,
            'last7DaysChart' => $this->last7DaysChart,
            'visitsTrendData' => $this->visitsTrendData,
            'patientLocationData' => $this->patientLocationData,
            'recentActivities' => $this->recentActivities,
            'dashboardSearchResults' => $this->dashboardSearchResults,
        ];

        $this->dispatch('dashboard-charts-update', chartData: [
            'visits' => $data['last7DaysChart'],
            'trend' => $data['visitsTrendData'],
            'location' => $data['patientLocationData'],
            'vitals' => $data['vitalSignsOverview'],
            'medicine' => $data['medicineInventory'],
        ]);

        return view('livewire.dashboard.clinic-staff-dashboard', $data);
    }

    public function resetFilters(): void
    {
        $this->dateRange = 'today';
        $this->patientType = 'all';
        $this->customStartDate = now()->startOfDay()->format('Y-m-d');
        $this->customEndDate = now()->endOfDay()->format('Y-m-d');
    }
}