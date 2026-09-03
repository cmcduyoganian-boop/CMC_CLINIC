<x-app-with-sidebar>
    <x-slot name="header">Appointments Report</x-slot>

    <div class="report-page">
        <!-- Header -->
        <div class="report-header">
            <div>
                <h1 class="report-title">Appointments Report</h1>
                <p class="report-subtitle">Appointment statistics and status breakdown</p>
            </div>
            <div class="header-actions">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ route('reports.download', 'appointments') }}" class="btn btn-download">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="card clickable-card" data-filter="all">
                <h3>Total Appointments</h3>
                <p class="card-value">{{ $totalAppointments }}</p>
            </div>
            <div class="card clickable-card" data-filter="scheduled">
                <h3>Scheduled</h3>
                <p class="card-value">{{ $scheduled }}</p>
            </div>
            <div class="card clickable-card" data-filter="completed">
                <h3>Completed</h3>
                <p class="card-value">{{ $completed }}</p>
            </div>
            <div class="card clickable-card" data-filter="no-show">
                <h3>No-Show</h3>
                <p class="card-value">{{ $noShow }}</p>
            </div>
        </div>

        <!-- Chart -->
        <div class="chart-card">
            <h2 class="chart-title">Appointment Status Distribution</h2>
            <div class="chart-canvas-wrap">
                <canvas id="statusChart" data-scheduled="{{ $scheduled }}" data-completed="{{ $completed }}" data-noshow="{{ $noShow }}" data-cancelled="{{ $cancelled ?? 0 }}"></canvas>
            </div>
        </div>

        <!-- Appointments Table -->
        <div class="table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 class="table-title" style="margin: 0;">Recent Appointments</h2>
                <button id="clearFilterBtn" class="btn-clear-filter" style="display: none;">
                    <i class="fas fa-times-circle"></i> Clear Filter
                </button>
            </div>
            <div class="table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>DATE & TIME</th>
                            <th>PATIENT</th>
                            <th>CATEGORY</th>
                            <th>REASON</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appt)
                            @php
                                $filters = ['all', strtolower($appt->status)];
                            @endphp
                            <tr class="data-row" data-filters="{{ implode(',', $filters) }}">
                                <td>
                                    {{ $appt->appointment_date->format('M d, Y') }}<br>
                                    {{ date('h:i A', strtotime($appt->appointment_time)) }}
                                </td>
                                <td><strong>{{ $appt->patient->name }}</strong></td>
                                <td>
                                    <span class="badge {{ $appt->patient->getCategoryBadgeClass() }}">
                                        {{ $appt->patient->getCategoryLabel() }}
                                    </span>
                                </td>
                                <td>{{ $appt->reason ?? '-' }}</td>
                                <td>
                                    <span class="status-badge badge-{{ $appt->status }}">
                                        {{ $appt->getStatusLabel() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="report-footer">
            <p>Report generated from CMC Clinic Management System</p>
            <p>{{ now()->format('F d, Y H:i A') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        /* eslint-disable */
        // Initialize status chart
        const statusCanvas = document.getElementById('statusChart');
        const ctx = statusCanvas.getContext('2d');
        const scheduled = parseInt(statusCanvas.getAttribute('data-scheduled'));
        const completed = parseInt(statusCanvas.getAttribute('data-completed'));
        const noShow = parseInt(statusCanvas.getAttribute('data-noshow'));
        const cancelled = parseInt(statusCanvas.getAttribute('data-cancelled'));
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Scheduled', 'Completed', 'No-Show', 'Cancelled'],
                datasets: [{
                    data: [scheduled, completed, noShow, cancelled],
                    backgroundColor: ['#38bdf8', '#27ae60', '#fbbf24', '#f87171'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Filtering functionality
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.clickable-card');
            const rows = document.querySelectorAll('.data-row');
            const clearBtn = document.getElementById('clearFilterBtn');
            let currentFilter = null;

            cards.forEach(card => {
                card.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    if (currentFilter === filter) {
                        currentFilter = null;
                        cards.forEach(c => c.classList.remove('active'));
                        rows.forEach(r => r.classList.remove('hidden'));
                        clearBtn.style.display = 'none';
                    } else {
                        currentFilter = filter;
                        cards.forEach(c => c.classList.remove('active'));
                        this.classList.add('active');
                        
                        rows.forEach(row => {
                            const filters = row.getAttribute('data-filters').split(',');
                            if (filters.includes(filter)) {
                                row.classList.remove('hidden');
                            } else {
                                row.classList.add('hidden');
                            }
                        });
                        
                        clearBtn.style.display = 'flex';
                    }
                });
            });

            clearBtn.addEventListener('click', function() {
                currentFilter = null;
                cards.forEach(c => c.classList.remove('active'));
                rows.forEach(r => r.classList.remove('hidden'));
                clearBtn.style.display = 'none';
            });
        });
    </script>

    <style>
        .report-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .report-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .report-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-print {
            background: #3498db;
            color: white;
        }

        .btn-print:hover {
            background: #2980b9;
        }

        .btn-download {
            background: #27ae60;
            color: white;
        }

        .btn-download:hover {
            background: #229954;
        }

        .btn-back {
            background: var(--bg-input);
            color: var(--text-body);
        }

        .btn-back:hover {
            background: var(--border-inner);
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            position: sticky;
            top: 0;
            background: var(--bg-card);
            padding: 16px;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            border-radius: 8px;
        }

        .card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            text-align: center;
        }

        .card.clickable-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .card.clickable-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transform: translateY(-4px);
        }

        .card.clickable-card.active {
            border-color: #2980b9;
            box-shadow: 0 4px 12px rgba(41, 128, 185, 0.3);
            transform: scale(1.05);
        }

        .card h3 {
            margin: 0;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .card-value {
            margin: 12px 0 0 0;
            font-size: 32px;
            font-weight: 700;
            color: #38bdf8;
        }

        .chart-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            position: relative;
            height: 320px;
        }

        .chart-canvas-wrap {
            position: relative;
            height: 260px;
        }

        .chart-canvas-wrap canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
        }

        .chart-title {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
            border-bottom: 2px solid var(--border-inner);
            padding-bottom: 12px;
        }

        .table-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .table-title {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
            border-bottom: 2px solid var(--border-inner);
            padding-bottom: 12px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .report-table th {
            padding: 12px;
            text-align: left;
            background: var(--bg-input);
            font-weight: 700;
            color: var(--text-heading);
            border-bottom: 2px solid var(--border-inner);
            text-transform: uppercase;
            font-size: 11px;
        }

        .report-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-inner);
            color: var(--text-body);
        }

        .report-table tr:hover {
            background: var(--bg-input);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-student {
            background: rgba(56,189,248,0.15);
            color: #38bdf8;
        }

        .badge-faculty {
            background: rgba(139,92,246,0.15);
            color: #8b5cf6;
        }

        .badge-staff {
            background: rgba(39,174,96,0.15);
            color: #27ae60;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-scheduled {
            background: rgba(56,189,248,0.15);
            color: #38bdf8;
        }

        .badge-completed {
            background: rgba(39,174,96,0.15);
            color: #27ae60;
        }

        .badge-no-show {
            background: rgba(251, 191, 36, 0.15);
            color: #fbbf24;
        }

        .badge-cancelled {
            background: rgba(248, 113, 113, 0.15);
            color: #f87171;
        }

        .report-footer {
            text-align: center;
            padding: 20px;
            border-top: 2px solid var(--border-inner);
            color: var(--text-muted);
            font-size: 12px;
        }

        .btn-clear-filter {
            background: #f87171;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-clear-filter:hover {
            background: #ef4444;
        }

        .data-row.hidden {
            display: none;
        }

        @media print {
            .header-actions { display: none; }
            .btn-clear-filter { display: none !important; }
        }

        @media (max-width: 768px) {
            .header-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .summary-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</x-app-with-sidebar>