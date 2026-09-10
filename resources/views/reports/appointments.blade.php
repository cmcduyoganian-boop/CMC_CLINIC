<x-app-with-sidebar>
    <x-slot name="header">Appointments Report</x-slot>

    <div class="report-page">

        {{-- ── Header ── --}}
        <div class="report-header">
            <div class="header-actions">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ route('reports.download', ['type' => 'appointments', 'date' => $date ?? '', 'preset' => $preset ?? '']) }}" class="btn btn-download">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        {{-- ── Date Filter Bar ── --}}
        <div class="date-filter-bar">
            <form method="GET" action="{{ route('reports.appointments') }}" class="date-filter-form">
                <div class="dff-presets">
                    <a href="{{ route('reports.appointments') }}"                              class="dff-preset {{ !($date ?? null) && !($preset ?? null) ? 'active' : '' }}">All Time</a>
                    <a href="{{ route('reports.appointments', ['preset' => 'today']) }}"       class="dff-preset {{ ($preset ?? '') === 'today'  ? 'active' : '' }}">Today</a>
                    <a href="{{ route('reports.appointments', ['preset' => 'week']) }}"        class="dff-preset {{ ($preset ?? '') === 'week'   ? 'active' : '' }}">This Week</a>
                    <a href="{{ route('reports.appointments', ['preset' => 'month']) }}"       class="dff-preset {{ ($preset ?? '') === 'month'  ? 'active' : '' }}">This Month</a>
                </div>
                <div class="dff-range">
                    <span class="dff-label"><i class="fas fa-calendar-alt"></i> Filter by Date:</span>
                    <input type="date" name="date" value="{{ $date ?? '' }}" class="dff-input" max="{{ date('Y-m-d') }}">
                    <button type="submit" class="dff-apply"><i class="fas fa-filter"></i> Apply</button>
                    @if(!empty($date) || !empty($preset))
                        <a href="{{ route('reports.appointments') }}" class="dff-clear"><i class="fas fa-times"></i> Clear</a>
                    @endif
                </div>
            </form>
            @if(!empty($date) || !empty($preset))
                <div class="dff-result-info">
                    <i class="fas fa-info-circle"></i>
                    @if(!empty($date))
                        Showing <strong>{{ $filteredCount ?? 0 }}</strong> appointment(s) on <strong>{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</strong>
                    @else
                        Showing <strong>{{ $filteredCount ?? 0 }}</strong> appointment(s) — <strong>{{ ucfirst($preset) }}</strong>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── Summary Cards ── --}}
        <div class="appt-summary-grid">
            <div class="appt-card appt-total clickable-card" data-filter="all">
                <div class="appt-card-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">Total Appointments</p>
                    <p class="appt-card-value">{{ $totalAppointments }}</p>
                </div>
            </div>
            <div class="appt-card appt-scheduled clickable-card" data-filter="scheduled">
                <div class="appt-card-icon"><i class="fas fa-clock"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">Scheduled</p>
                    <p class="appt-card-value">{{ $scheduled }}</p>
                </div>
            </div>
            <div class="appt-card appt-completed clickable-card" data-filter="completed">
                <div class="appt-card-icon"><i class="fas fa-check-circle"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">Completed</p>
                    <p class="appt-card-value">{{ $completed }}</p>
                </div>
            </div>
            <div class="appt-card appt-noshow clickable-card" data-filter="no-show">
                <div class="appt-card-icon"><i class="fas fa-user-times"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">No-Show</p>
                    <p class="appt-card-value">{{ $noShow }}</p>
                </div>
            </div>
            <div class="appt-card appt-cancelled clickable-card" data-filter="cancelled">
                <div class="appt-card-icon"><i class="fas fa-times-circle"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">Cancelled</p>
                    <p class="appt-card-value">{{ $cancelled ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- ── Chart + Stats row ── --}}
        <div class="appt-chart-row">
            <div class="appt-chart-card">
                <h2 class="section-title"><i class="fas fa-chart-pie"></i> Status Distribution</h2>
                <div class="donut-wrap">
                    <canvas id="statusChart"
                        data-scheduled="{{ $scheduled }}"
                        data-completed="{{ $completed }}"
                        data-noshow="{{ $noShow }}"
                        data-cancelled="{{ $cancelled ?? 0 }}">
                    </canvas>
                </div>
            </div>

            <div class="appt-stats-card">
                <h2 class="section-title"><i class="fas fa-info-circle"></i> Quick Stats</h2>
                @php
                    $total      = $totalAppointments ?: 1;
                    $compRate   = round(($completed / $total) * 100);
                    $noShowRate = round(($noShow    / $total) * 100);
                    $schedRate  = round(($scheduled / $total) * 100);
                    $canxRate   = round((($cancelled ?? 0) / $total) * 100);
                @endphp
                <div class="stat-row">
                    <span class="stat-label"><span class="stat-dot dot-blue"></span>Scheduled</span>
                    <div class="stat-bar-wrap">
                        <div class="stat-bar" style="width: {{ $schedRate }}%; background: #38bdf8;"></div>
                    </div>
                    <span class="stat-pct">{{ $schedRate }}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label"><span class="stat-dot dot-green"></span>Completed</span>
                    <div class="stat-bar-wrap">
                        <div class="stat-bar" style="width: {{ $compRate }}%; background: #27ae60;"></div>
                    </div>
                    <span class="stat-pct">{{ $compRate }}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label"><span class="stat-dot dot-yellow"></span>No-Show</span>
                    <div class="stat-bar-wrap">
                        <div class="stat-bar" style="width: {{ $noShowRate }}%; background: #f39c12;"></div>
                    </div>
                    <span class="stat-pct">{{ $noShowRate }}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label"><span class="stat-dot dot-red"></span>Cancelled</span>
                    <div class="stat-bar-wrap">
                        <div class="stat-bar" style="width: {{ $canxRate }}%; background: #e74c3c;"></div>
                    </div>
                    <span class="stat-pct">{{ $canxRate }}%</span>
                </div>

                <div class="completion-highlight">
                    <i class="fas fa-trophy"></i>
                    <div>
                        <p class="ch-label">Completion Rate</p>
                        <p class="ch-value">{{ $compRate }}%</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Appointments Table ── --}}
        <div class="appt-table-card">
            <div class="table-topbar">
                <h2 class="section-title" style="margin:0"><i class="fas fa-list"></i> Appointments List</h2>
                <button id="clearFilterBtn" class="clear-filter-btn" style="display:none;">
                    <i class="fas fa-times-circle"></i> Clear Filter
                </button>
            </div>

            @if($appointments->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>No appointments found for the selected period.</p>
                </div>
            @else
                <div class="table-scroll">
                    <table class="appt-table">
                        <colgroup>
                            <col style="width:140px">
                            <col>
                            <col style="width:110px">
                            <col>
                            <col style="width:110px">
                            <col style="width:70px">
                        </colgroup>
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-user"></i> Patient</th>
                                <th><i class="fas fa-tag"></i> Category</th>
                                <th><i class="fas fa-comment-medical"></i> Reason</th>
                                <th><i class="fas fa-circle-dot"></i> Status</th>
                                <th><i class="fas fa-clock"></i> Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appt)
                                @php $filters = ['all', strtolower($appt->status)]; @endphp
                                <tr class="data-row" data-filters="{{ implode(',', $filters) }}">
                                    <td>
                                        <span class="date-main">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="patient-cell">
                                            <span class="patient-avatar">{{ strtoupper(substr($appt->patient->name ?? '?', 0, 1)) }}</span>
                                            <div>
                                                <p class="patient-name">{{ $appt->patient->name ?? '—' }}</p>
                                                @if($appt->patient->year_section)
                                                    <p class="patient-sub">{{ $appt->patient->year_section }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="cat-badge cat-{{ $appt->patient->category ?? 'other' }}">
                                            {{ ucfirst($appt->patient->category ?? '—') }}
                                        </span>
                                    </td>
                                    <td class="reason-cell">{{ $appt->reason ?? '—' }}</td>
                                    <td>
                                        <span class="status-pill status-{{ $appt->status }}">
                                            @if($appt->status === 'scheduled')   <i class="fas fa-clock"></i>
                                            @elseif($appt->status === 'completed') <i class="fas fa-check"></i>
                                            @elseif($appt->status === 'no-show')   <i class="fas fa-user-times"></i>
                                            @else <i class="fas fa-times"></i>
                                            @endif
                                            {{ ucfirst($appt->status) }}
                                        </span>
                                    </td>
                                    <td class="time-cell">
                                        @if($appt->appointment_time)
                                            {{ date('h:i A', strtotime($appt->appointment_time)) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ── Footer ── --}}
        <div class="report-foot">
            <i class="fas fa-hospital"></i>
            CMC Clinic Management System &nbsp;·&nbsp; Generated {{ now()->format('F d, Y \a\t h:i A') }}
        </div>

    </div>

    {{-- ── Chart JS ── --}}
    <script>
    (function() {
        const canvas = document.getElementById('statusChart');
        if (!canvas) return;
        const s = parseInt(canvas.dataset.scheduled  || 0);
        const c = parseInt(canvas.dataset.completed  || 0);
        const n = parseInt(canvas.dataset.noshow     || 0);
        const x = parseInt(canvas.dataset.cancelled  || 0);

        new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Scheduled', 'Completed', 'No-Show', 'Cancelled'],
                datasets: [{
                    data: [s, c, n, x],
                    backgroundColor: ['#38bdf8', '#27ae60', '#f39c12', '#e74c3c'],
                    borderColor: 'transparent',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#94a3b8', font: { size: 12 }, padding: 16 }
                    }
                }
            }
        });
    })();

    // Card filter click
    document.addEventListener('DOMContentLoaded', function () {
        const cards   = document.querySelectorAll('.clickable-card');
        const rows    = document.querySelectorAll('.data-row');
        const clearBtn = document.getElementById('clearFilterBtn');
        let active = null;

        cards.forEach(card => {
            card.addEventListener('click', function () {
                const f = this.dataset.filter;
                if (active === f) {
                    active = null;
                    cards.forEach(c => c.classList.remove('is-active'));
                    rows.forEach(r => r.classList.remove('hidden'));
                    if (clearBtn) clearBtn.style.display = 'none';
                } else {
                    active = f;
                    cards.forEach(c => c.classList.remove('is-active'));
                    this.classList.add('is-active');
                    rows.forEach(r => {
                        const rf = r.dataset.filters.split(',');
                        r.classList.toggle('hidden', !rf.includes(f));
                    });
                    if (clearBtn) clearBtn.style.display = 'flex';
                }
            });
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                active = null;
                cards.forEach(c => c.classList.remove('is-active'));
                rows.forEach(r => r.classList.remove('hidden'));
                this.style.display = 'none';
            });
        }
    });
    </script>

    <style>
        /* ── Page Layout ──────────────────────────────────────────── */
        .report-page { display: flex; flex-direction: column; gap: 22px; }

        .report-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border-radius: 8px; border: none;
            font-size: 13px; font-weight: 700; cursor: pointer;
            text-decoration: none; transition: opacity 0.2s;
            font-family: inherit;
        }
        .btn:hover { opacity: 0.85; }
        .btn-print    { background: #2980b9;              color: #fff; }
        .btn-download { background: #27ae60;              color: #fff; }
        .btn-back     { background: var(--bg-input);      color: var(--text-body); border: 1px solid var(--border-card); }

        /* ── Summary Cards ────────────────────────────────────────── */
        .appt-summary-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }

        .appt-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            padding: 18px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }
        .appt-card:hover  { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .appt-card.is-active { box-shadow: 0 4px 16px rgba(56,189,248,0.2); }

        .appt-total     { border-left-color: #38bdf8; }
        .appt-scheduled { border-left-color: #38bdf8; }
        .appt-completed { border-left-color: #27ae60; }
        .appt-noshow    { border-left-color: #f39c12; }
        .appt-cancelled { border-left-color: #e74c3c; }

        .appt-total.is-active     { background: rgba(56,189,248,0.07); }
        .appt-scheduled.is-active { background: rgba(56,189,248,0.07); }
        .appt-completed.is-active { background: rgba(39,174,96,0.07);  }
        .appt-noshow.is-active    { background: rgba(243,156,18,0.07); }
        .appt-cancelled.is-active { background: rgba(231,76,60,0.07);  }

        .appt-card-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .appt-total     .appt-card-icon { background: rgba(56,189,248,0.15); color: #38bdf8; }
        .appt-scheduled .appt-card-icon { background: rgba(56,189,248,0.15); color: #38bdf8; }
        .appt-completed .appt-card-icon { background: rgba(39,174,96,0.15);  color: #27ae60; }
        .appt-noshow    .appt-card-icon { background: rgba(243,156,18,0.15); color: #f39c12; }
        .appt-cancelled .appt-card-icon { background: rgba(231,76,60,0.15);  color: #e74c3c; }

        .appt-card-label {
            margin: 0; font-size: 10px; font-weight: 700;
            text-transform: uppercase; color: var(--text-muted); letter-spacing: .5px;
        }
        .appt-card-value {
            margin: 4px 0 0; font-size: 26px; font-weight: 800;
            color: var(--text-heading);
        }

        /* ── Chart + Stats row ────────────────────────────────────── */
        .appt-chart-row {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
        }

        .appt-chart-card,
        .appt-stats-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            padding: 22px;
        }

        .section-title {
            margin: 0 0 18px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-inner);
        }
        .section-title i { color: #38bdf8; }

        .donut-wrap {
            position: relative;
            height: 220px;
        }

        /* ── Stat bars ────────────────────────────────────────────── */
        .stat-row {
            display: grid;
            grid-template-columns: 110px 1fr 40px;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .stat-label {
            font-size: 12px;
            color: var(--text-body);
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .stat-dot {
            width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        }
        .dot-blue   { background: #38bdf8; }
        .dot-green  { background: #27ae60; }
        .dot-yellow { background: #f39c12; }
        .dot-red    { background: #e74c3c; }

        .stat-bar-wrap {
            height: 8px; background: var(--bg-input);
            border-radius: 4px; overflow: hidden;
        }
        .stat-bar { height: 100%; border-radius: 4px; transition: width 0.6s ease; }
        .stat-pct { font-size: 11px; font-weight: 700; color: var(--text-muted); text-align: right; }

        .completion-highlight {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 22px;
            padding: 14px 18px;
            background: linear-gradient(135deg, rgba(39,174,96,0.12), rgba(56,189,248,0.08));
            border: 1px solid rgba(39,174,96,0.3);
            border-radius: 10px;
        }
        .completion-highlight i { font-size: 22px; color: #f39c12; }
        .ch-label { margin: 0; font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
        .ch-value { margin: 2px 0 0; font-size: 24px; font-weight: 800; color: #27ae60; }

        /* ── Table ────────────────────────────────────────────────── */
        .appt-table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            padding: 22px;
        }

        .table-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .clear-filter-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(231,76,60,0.12); color: #e74c3c;
            border: 1px solid rgba(231,76,60,0.3);
            border-radius: 6px; padding: 6px 14px;
            font-size: 12px; font-weight: 700; cursor: pointer;
            font-family: inherit; transition: background 0.2s;
        }
        .clear-filter-btn:hover { background: rgba(231,76,60,0.22); }

        .table-scroll { overflow-x: auto; }

        .appt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .appt-table thead tr {
            background: linear-gradient(135deg, #2980b9, #1a6ea8);
        }

        .appt-table th {
            padding: 12px 14px;
            text-align: left;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        .appt-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-inner);
            color: var(--text-heading);
            vertical-align: middle;
        }

        .appt-table tbody tr:hover { background: var(--bg-input); }
        .appt-table tbody tr:last-child td { border-bottom: none; }

        .data-row.hidden { display: none; }

        /* Date cell */
        .date-main { font-size: 12px; font-weight: 600; color: var(--text-body); }

        /* Patient cell */
        .patient-cell { display: flex; align-items: center; gap: 10px; }
        .patient-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: #fff; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .patient-name { margin: 0; font-size: 13px; font-weight: 700; color: var(--text-heading); }
        .patient-sub  { margin: 2px 0 0; font-size: 11px; color: var(--text-muted); }

        /* Category badges */
        .cat-badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700; white-space: nowrap;
        }
        .cat-student  { background: rgba(56,189,248,0.15);  color: #38bdf8; }
        .cat-faculty  { background: rgba(139,92,246,0.15); color: #8b5cf6; }
        .cat-staff    { background: rgba(39,174,96,0.15);  color: #27ae60; }
        .cat-other    { background: rgba(100,116,139,0.15); color: #94a3b8; }

        /* Status pills */
        .status-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700; white-space: nowrap;
        }
        .status-scheduled  { background: rgba(56,189,248,0.15); color: #38bdf8;  border: 1px solid rgba(56,189,248,0.3); }
        .status-completed  { background: rgba(39,174,96,0.15);  color: #27ae60;  border: 1px solid rgba(39,174,96,0.3);  }
        .status-no-show    { background: rgba(243,156,18,0.15); color: #f39c12;  border: 1px solid rgba(243,156,18,0.3); }
        .status-cancelled  { background: rgba(231,76,60,0.15);  color: #e74c3c;  border: 1px solid rgba(231,76,60,0.3);  }

        .reason-cell { font-size: 12px; color: var(--text-body); max-width: 200px; }
        .time-cell   { font-size: 12px; color: var(--text-muted); white-space: nowrap; }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 48px 24px; color: var(--text-muted);
        }
        .empty-state i { font-size: 36px; margin-bottom: 12px; display: block; color: var(--border-inner); }
        .empty-state p { margin: 0; font-size: 14px; }

        /* Footer */
        .report-foot {
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            padding: 12px 0;
            border-top: 1px solid var(--border-inner);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }

        /* ── Responsive ───────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .appt-summary-grid { grid-template-columns: repeat(3, 1fr); }
            .appt-chart-row    { grid-template-columns: 1fr; }
            .donut-wrap        { height: 200px; }
        }
        @media (max-width: 600px) {
            .appt-summary-grid { grid-template-columns: repeat(2, 1fr); }
            .header-actions    { flex-wrap: wrap; }
        }

        @media print {
            .header-actions, .clear-filter-btn, .date-filter-bar { display: none !important; }
        }
    </style>

</x-app-with-sidebar>