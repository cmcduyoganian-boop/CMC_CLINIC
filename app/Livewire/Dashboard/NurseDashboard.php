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
    public $dateRange = 'today';
    public $visitType = 'all';
    public $patientType = 'all';
    public ?string $customStartDate = null;
    public ?string $customEndDate = null;
    public $dashboardSearch = '';

    // ============ MODAL ============
    public $showActivitiesModal = false;

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

    // ============ KPI: ABNORMAL / CRITICAL VITALS ============
    public function getAbnormalVitals()
    {
        [$start, $end] = $this->getDateRange();

        $query = ClinicVisit::whereBetween('created_at', [$start, $end])
            ->where(function ($q) {
                // Temperature: Abnormal if <35.0 or ≥38.0
                $q->where(fn($s) => $s->whereNotNull('temperature')->where('temperature', '<', 35.0))
                  ->orWhere(fn($s) => $s->whereNotNull('temperature')->where('temperature', '>=', 38.0))
                  // Pulse Rate: Abnormal if <50 or >120
                  ->orWhere(fn($s) => $s->whereNotNull('pulse_rate')->where('pulse_rate', '<', 50))
                  ->orWhere(fn($s) => $s->whereNotNull('pulse_rate')->where('pulse_rate', '>', 120))
                  // Respiratory Rate: Abnormal if <8 or >30
                  ->orWhere(fn($s) => $s->whereNotNull('respiratory_rate')->where('respiratory_rate', '<', 8))
                  ->orWhere(fn($s) => $s->whereNotNull('respiratory_rate')->where('respiratory_rate', '>', 30))
                  // Systolic BP: Abnormal if <80 or ≥180
                  ->orWhere(fn($s) => $s->whereNotNull('bp_systolic')->where('bp_systolic', '<', 80))
                  ->orWhere(fn($s) => $s->whereNotNull('bp_systolic')->where('bp_systolic', '>=', 180))
                  // Diastolic BP: Abnormal if <50 or ≥120
                  ->orWhere(fn($s) => $s->whereNotNull('bp_diastolic')->where('bp_diastolic', '<', 50))
                  ->orWhere(fn($s) => $s->whereNotNull('bp_diastolic')->where('bp_diastolic', '>=', 120))
                  // SpO2: Abnormal if ≤90
                  ->orWhere(fn($s) => $s->whereNotNull('spo2')->where('spo2', '<=', 90));
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

    // ============ CHART DATA: VISITS BAR/LINE (respects filters) ============
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

    // ============ CHART DATA: VISITS TREND (always min 7 days for context) ============
    public function getVisitsTrendData()
    {
        [$start, $end] = $this->getDateRange();

        $daysDiff = $start->diffInDays($end);

        // Always show at least 7 days so the trend line is meaningful
        if ($daysDiff < 6) {
            $start = now()->subDays(6)->startOfDay();
            $end   = now()->endOfDay();
            $daysDiff = 6;
        }

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
            $data   = $visits->pluck('count')->toArray();
        } else {
            // Daily data — fill every day in range (including zeros)
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
                ->pluck('count', 'date');

            $labels = [];
            $data   = [];
            for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
                $key      = $day->format('Y-m-d');
                $labels[] = $day->format('M d');
                $data[]   = (int) ($visits[$key] ?? 0);
            }
        }

        return compact('labels', 'data');
    }

    // ============ NORMALIZE ADDRESS for grouping ============
    private function normalizeAddress(string $address): string
    {
        // Lowercase, strip punctuation, collapse spaces, sort words for consistent grouping
        $clean = strtolower(trim($address));
        $clean = preg_replace('/[^\w\s]/', ' ', $clean); // remove punctuation
        $clean = preg_replace('/\s+/', ' ', $clean);      // collapse whitespace
        $words = array_filter(explode(' ', $clean));
        sort($words);
        return implode(' ', $words);
    }

    public function getPatientLocationData()
    {
        $visits = $this->buildVisitQuery()
            ->with('patient:id,address')
            ->get();

        // Group by normalized address, then display the most common raw form
        $grouped = $visits->groupBy(function ($visit) {
            $address = trim((string) ($visit->patient?->address ?? ''));
            if ($address === '') return '__unknown__';
            return $this->normalizeAddress($address);
        });

        // For each normalized group, pick the most frequent raw address as the label
        $locations = $grouped->map(function ($groupVisits, $key) {
            if ($key === '__unknown__') return ['label' => 'Address not provided', 'count' => $groupVisits->count()];

            $rawCounts = $groupVisits->groupBy(fn ($v) => trim((string) ($v->patient?->address ?? '')))
                ->map->count()
                ->sortDesc();

            return ['label' => $rawCounts->keys()->first(), 'count' => $groupVisits->count()];
        })
        ->sortByDesc('count')
        ->take(10);

        $labels = $locations->pluck('label')->values()->toArray();
        $data   = $locations->pluck('count')->values()->map(fn($c) => (int) $c)->toArray();

        return [
            'labels'      => $labels,
            'data'        => $data,
            'rankedLocations' => $locations->map(fn ($location) => [
                'label' => $location['label'],
                'count' => (int) $location['count'],
            ])->values()->toArray(),
            'topLocation' => $labels[0] ?? 'No location data',
            'topCount'    => $data[0] ?? 0,
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

        $visits = $query->get(['temperature', 'pulse_rate', 'respiratory_rate',
                               'bp_systolic', 'bp_diastolic', 'spo2', 'height', 'weight']);

        $total    = $visits->count();
        $abnormal = 0;
        $elevated = 0; // above_normal or below_normal
        $normal   = 0;

        foreach ($visits as $visit) {
            $assessment = $visit->getVitalSignsAssessment();
            $overall    = $assessment['overall'];

            if ($overall === \App\Support\VitalSigns::ABNORMAL) {
                $abnormal++;
            } elseif (in_array($overall, [\App\Support\VitalSigns::ABOVE_NORMAL, \App\Support\VitalSigns::BELOW_NORMAL])) {
                $elevated++;
            } else {
                $normal++;
            }
        }

        return [
            'normal'   => $normal,
            'elevated' => $elevated,
            'abnormal' => $abnormal,
            'total'    => $total,
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

    public function getDashboardSearchResults()
    {
        $term = trim($this->dashboardSearch);

        if (strlen($term) < 2) {
            return [];
        }

        return Patient::whereHas('clinicVisits')
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('year_section', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->map(function ($patient) {
                $latestVisit = $patient->clinicVisits()->latest('visit_date')->first();

                return [
                    'name' => $patient->name,
                    'category' => ucfirst($patient->category ?? 'Patient'),
                    'visitId' => $latestVisit?->id,
                ];
            })
            ->all();
    }

    // ============ MODAL: OPEN / CLOSE ============
    public function openActivitiesModal(): void
    {
        $this->showActivitiesModal = true;
    }

    public function closeActivitiesModal(): void
    {
        $this->showActivitiesModal = false;
    }

    // ============ ALL ACTIVITIES (for modal — last 50) ============
    public function getAllActivities(): array
    {
        $activities = [];

        // All clinic visits (last 100, we'll sort and trim)
        $visits = ClinicVisit::with('patient')
            ->orderBy('created_at', 'desc')
            ->limit(30)
            ->get()
            ->map(function ($visit) {
                return [
                    'type'      => 'visit',
                    'icon'      => 'fa-stethoscope',
                    'color'     => 'blue',
                    'message'   => ($visit->patient->name ?? 'Patient') . ' — Clinic Visit Recorded',
                    'detail'    => $visit->visit_type ? ucfirst(str_replace('_', ' ', $visit->visit_type)) : null,
                    'timestamp' => $visit->created_at,
                    'link'      => route('clinic-visit.index'),
                ];
            });
        $activities = array_merge($activities, $visits->toArray());

        // All appointments
        $appointments = Appointment::with('patient')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($apt) {
                return [
                    'type'      => 'appointment',
                    'icon'      => 'fa-calendar-check',
                    'color'     => 'green',
                    'message'   => ($apt->patient->name ?? 'Patient') . ' — Appointment ' . ucfirst($apt->status ?? 'Scheduled'),
                    'detail'    => $apt->appointment_date ? \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') : null,
                    'timestamp' => $apt->created_at,
                    'link'      => route('appointments.index'),
                ];
            });
        $activities = array_merge($activities, $appointments->toArray());

        // Low stock alerts
        $lowStock = Medicine::whereRaw('quantity <= minimum_stock')
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($med) {
                return [
                    'type'      => 'inventory',
                    'icon'      => 'fa-exclamation-triangle',
                    'color'     => 'orange',
                    'message'   => $med->name . ' — Low Stock Alert',
                    'detail'    => 'Qty: ' . $med->quantity . ' (Min: ' . $med->minimum_stock . ')',
                    'timestamp' => $med->updated_at,
                    'link'      => route('medicines.index'),
                ];
            });
        $activities = array_merge($activities, $lowStock->toArray());

        // Expiring medicines
        $expiringSoon = Medicine::where('status', 'active')
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '>=', now())
            ->whereDate('expiration_date', '<=', now()->addDays(30))
            ->orderBy('expiration_date')
            ->limit(10)
            ->get()
            ->map(function ($med) {
                return [
                    'type'      => 'inventory',
                    'icon'      => 'fa-hourglass-half',
                    'color'     => 'orange',
                    'message'   => $med->name . ' — Expiring Soon',
                    'detail'    => 'Expires: ' . $med->expiration_date->format('M d, Y'),
                    'timestamp' => $med->updated_at,
                    'link'      => route('medicines.index'),
                ];
            });
        $activities = array_merge($activities, $expiringSoon->toArray());

        // Pending user approvals
        $pendingUsers = User::where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'type'      => 'user',
                    'icon'      => 'fa-user-clock',
                    'color'     => 'purple',
                    'message'   => $user->name . ' — Pending Account Approval',
                    'detail'    => $user->email ?? null,
                    'timestamp' => $user->created_at,
                    'link'      => route('admin.users'),
                ];
            });
        $activities = array_merge($activities, $pendingUsers->toArray());

        // Sort by timestamp desc, limit to 50
        usort($activities, fn($a, $b) => $b['timestamp']->timestamp <=> $a['timestamp']->timestamp);

        return array_slice($activities, 0, 50);
    }

    public function render()
    {
        $data = [
            'visitsToday'         => $this->getVisitsToday(),
            'lowStockMedicines'   => $this->getLowStockMedicines(),
            'pendingAppointments' => $this->getPendingAppointments(),
            'abnormalVitals'      => $this->getAbnormalVitals(),
            'pendingUserApprovals'=> $this->getPendingUserApprovals(),
            'visitsTrendData'     => $this->getVisitsTrendData(),
            'patientLocationData' => $this->getPatientLocationData(),
            'last7DaysChart'      => $this->getLast7DaysChart(),
            'vitalSignsOverview'  => $this->getVitalSignsOverview(),
            'medicineInventory'   => $this->getMedicineInventoryStatus(),
            'appointmentStats'    => $this->getAppointmentStats(),
            'recentActivities'    => $this->getRecentActivities(),
            'allActivities'       => $this->showActivitiesModal ? $this->getAllActivities() : [],
            'dashboardSearchResults' => $this->getDashboardSearchResults(),
        ];

        // Dispatch chart data to JS after every render so graphs stay reactive
        $this->dispatch('dashboard-charts-update', chartData: [
            'visits'       => $data['last7DaysChart'],
            'trend'        => $data['visitsTrendData'],
            'location'     => $data['patientLocationData'],
            'vitals'       => $data['vitalSignsOverview'],
            'appointments' => $data['appointmentStats'],
            'medicine'     => $data['medicineInventory'],
        ]);

        return view('livewire.dashboard.nurse-dashboard', $data);
    }

    public function resetFilters()
    {
        $this->dateRange   = 'today';
        $this->visitType   = 'all';
        $this->patientType = 'all';
    }
}