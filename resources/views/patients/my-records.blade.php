<x-app-with-sidebar>
    <x-slot name="header">My Records</x-slot>

    <div class="my-records-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">My Records</h1>
                <p class="page-subtitle">Your clinic visit history and account details</p>
            </div>
        </div>

        <div class="profile-summary">
            <div class="avatar-box">{{ strtoupper(substr($patient->name, 0, 1)) }}</div>
            <div>
                <h2>{{ $patient->name }}</h2>
                <p>
                    <span class="badge {{ $patient->getCategoryBadgeClass() }}">{{ $patient->getCategoryLabel() }}</span>
                    @if($patient->program)
                        <span class="meta-pill">{{ $patient->program }}</span>
                    @endif
                    @if($patient->year_section)
                        <span class="meta-pill">{{ $patient->year_section }}</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="records-layout">
            <div class="info-panel card">
                <h3>Personal Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Phone</label>
                        <span>{{ $patient->phone ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Age</label>
                        <span>{{ $patient->age ?? '-' }}</span>
                    </div>
                    <div class="info-item full-width">
                        <label>Address</label>
                        <span>{{ $patient->address ?? '-' }}</span>
                    </div>
                    <div class="info-item full-width">
                        <label>Email</label>
                        <span>{{ $patient->email ?? auth()->user()->email }}</span>
                    </div>
                </div>
            </div>

            <div class="stats-panel card">
                <h3>Summary</h3>
                <div class="stats-grid">
                    <div class="stat-box">
                        <span class="stat-number">{{ $patient->clinicVisits->count() }}</span>
                        <span class="stat-label">Visits</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">{{ $patient->clinicVisits->where('diagnosis', '!=', null)->count() }}</span>
                        <span class="stat-label">Diagnosed</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card visits-panel">
            <div class="section-header">
                <h3>Clinic Visit History</h3>
            </div>

            @if($patient->clinicVisits->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>No clinic visits recorded yet.</p>
                </div>
            @else
                <div class="visits-list">
                    @foreach($patient->clinicVisits as $visit)
                        <div class="visit-item">
                            <div class="visit-date-box">
                                <span class="day">{{ $visit->visit_date ? $visit->visit_date->format('d') : '-' }}</span>
                                <span class="month">{{ $visit->visit_date ? $visit->visit_date->format('M') : '-' }}</span>
                            </div>
                            <div class="visit-body">
                                <div class="visit-topline">
                                    <strong>{{ $visit->visit_date ? $visit->visit_date->format('F d, Y') : 'Date not set' }}</strong>
                                    <span class="visit-type">{{ $visit->visit_type ?? 'Clinic Visit' }}</span>
                                </div>

                                @if($visit->diagnosis)
                                    <p><span class="label">Diagnosis:</span> {{ $visit->diagnosis }}</p>
                                @endif

                                @if($visit->management)
                                    <p><span class="label">Management:</span> {{ $visit->management }}</p>
                                @endif

                                @if($visit->notes)
                                    <p><span class="label">Notes:</span> {{ $visit->notes }}</p>
                                @endif

                                @if($visit->temperature || $visit->pulse_rate || $visit->bp_systolic || $visit->spo2)
                                    <div class="vitals-inline">
                                        @if($visit->temperature)
                                            <span>Temp: {{ $visit->temperature }}°C</span>
                                        @endif
                                        @if($visit->pulse_rate)
                                            <span>Pulse: {{ $visit->pulse_rate }} bpm</span>
                                        @endif
                                        @if($visit->bp_systolic && $visit->bp_diastolic)
                                            <span>BP: {{ $visit->bp_systolic }}/{{ $visit->bp_diastolic }}</span>
                                        @endif
                                        @if($visit->spo2)
                                            <span>SpO₂: {{ $visit->spo2 }}%</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        .my-records-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .page-subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-back, .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-back {
            background: var(--bg-input);
            color: var(--text-heading);
            border: 1px solid var(--border-input);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-back:hover { background: var(--border-input); }
        .btn-primary:hover { background: linear-gradient(135deg, #2980b9, #1f6ea5); }

        .profile-summary {
            background: var(--bg-card);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .avatar-box {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
        }

        .profile-summary h2 {
            margin: 0;
            color: var(--text-heading);
            font-size: 24px;
        }

        .profile-summary p {
            margin: 8px 0 0;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-student { background: #dbeafe; color: #1d4ed8; }
        .badge-faculty { background: #ede9fe; color: #6d28d9; }
        .badge-staff { background: #dcfce7; color: #15803d; }

        .meta-pill {
            background: var(--bg-input);
            color: var(--text-heading);
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        .records-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card {
            background: var(--bg-card);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 20px;
        }

        .card h3 {
            margin: 0 0 16px;
            color: var(--text-heading);
            font-size: 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(140px, 1fr));
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-item label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
        }

        .info-item span {
            color: var(--text-heading);
            font-weight: 600;
            font-size: 13px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(110px, 1fr));
            gap: 12px;
        }

        .stat-box {
            background: var(--bg-input);
            border-radius: 8px;
            padding: 18px 12px;
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .stat-label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
        }

        .visits-panel {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--border-inner);
            padding-bottom: 12px;
        }

        .visits-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .visit-item {
            display: flex;
            gap: 16px;
            padding: 16px;
            border-radius: 10px;
            background: var(--bg-input);
            border: 1px solid var(--border-card);
        }

        .visit-date-box {
            min-width: 62px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 6px;
        }

        .visit-date-box .day {
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
        }

        .visit-date-box .month {
            font-size: 11px;
            font-weight: 700;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        .visit-body {
            flex: 1;
        }

        .visit-topline {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            align-items: center;
            margin-bottom: 6px;
        }

        .visit-topline strong {
            color: var(--text-heading);
            font-size: 14px;
        }

        .visit-type {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .visit-body p {
            margin: 6px 0;
            color: var(--text-body);
            font-size: 13px;
            line-height: 1.5;
        }

        .label {
            font-weight: 700;
            color: var(--text-heading);
        }

        .vitals-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .vitals-inline span {
            background: var(--bg-input);
            color: var(--text-heading);
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 30px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 40px;
            opacity: 0.4;
            display: block;
            margin-bottom: 10px;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .page-header,
            .header-actions,
            .records-layout {
                flex-direction: column;
                display: flex;
            }

            .records-layout {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-with-sidebar>
