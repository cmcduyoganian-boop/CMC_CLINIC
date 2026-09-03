<x-app-with-sidebar>
    <x-slot name="header">Clinic Visits Report</x-slot>

    <div class="report-page">
        <!-- Header -->
        <div class="report-header">
            <div>
                <h1 class="report-title">Clinic Visits Report</h1>
                <p class="report-subtitle">Generated on {{ now()->format('F d, Y \a\t H:i A') }}</p>
            </div>
            <div class="header-actions">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ route('reports.download', 'clinic-visits') }}" class="btn btn-download">
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
                <h3>Total Visits</h3>
                <p class="card-value">{{ $totalVisits }}</p>
            </div>
            <div class="card clickable-card" data-filter="today">
                <h3>Today's Visits</h3>
                <p class="card-value">{{ $todayVisits }}</p>
            </div>
            <div class="card clickable-card" data-filter="month">
                <h3>This Month</h3>
                <p class="card-value">{{ $monthVisits }}</p>
            </div>
            <div class="card clickable-card" data-filter="unique">
                <h3>Unique Patients</h3>
                <p class="card-value">{{ $uniquePatients }}</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-card full-width">
                <h2 class="chart-title">Visits Trend - Last 30 Days</h2>
                <div class="chart-canvas-wrap">
                    <canvas id="trendsChart" data-labels='{!! json_encode($last30Days->pluck('date')) !!}' data-counts='{!! json_encode($last30Days->pluck('count')) !!}'></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Visits Table -->
        <div class="table-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 class="table-title" style="margin: 0;">Recent Clinic Visits</h2>
                <button id="clearFilterBtn" class="btn-clear-filter" style="display: none;">
                    <i class="fas fa-times-circle"></i> Clear Filter
                </button>
            </div>
            <div class="table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>PATIENT</th>
                            <th>CATEGORY</th>
                            <th>DIAGNOSIS</th>
                            <th>TEMPERATURE</th>
                            <th>BP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentVisits as $visit)
                            @php
                                $filters = ['all'];
                                if ($visit->visit_date->isToday()) $filters[] = 'today';
                                if ($visit->visit_date->month === now()->month && $visit->visit_date->year === now()->year) $filters[] = 'month';
                            @endphp
                            <tr class="data-row" data-filters="{{ implode(',', $filters) }}">
                                <td>{{ $visit->visit_date->format('M d, Y') }}</td>
                                <td><strong>{{ $visit->patient->name }}</strong></td>
                                <td>
                                    <span class="badge {{ $visit->patient->getCategoryBadgeClass() }}">
                                        {{ $visit->patient->getCategoryLabel() }}
                                    </span>
                                </td>
                                <td>{{ $visit->diagnosis ?? '-' }}</td>
                                <td>{{ $visit->temperature ? $visit->temperature . '°C' : '-' }}</td>
                                <td>{{ $visit->bp_systolic && $visit->bp_diastolic ? $visit->bp_systolic . '/' . $visit->bp_diastolic : '-' }}</td>
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
        // Initialize trends chart
        const trendsCanvas = document.getElementById('trendsChart');
        const trendsCtx = trendsCanvas.getContext('2d');
        const chartLabels = JSON.parse(trendsCanvas.getAttribute('data-labels'));
        const chartCounts = JSON.parse(trendsCanvas.getAttribute('data-counts'));
        
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Visits',
                    data: chartCounts,
                    borderColor: '#38bdf8',
                    backgroundColor: 'rgba(56, 189, 248, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#38bdf8'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#94a3b8' }, grid: { color: '#e2e8f0' } },
                    x: { ticks: { color: '#94a3b8', maxRotation: 45, minRotation: 0 }, grid: { color: '#e2e8f0' } }
                },
                plugins: { legend: { display: false } }
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-4px);
        }

        .card.clickable-card.active {
            border-color: #38bdf8;
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
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

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .chart-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            position: relative;
            height: 320px;
        }

        .chart-card.full-width {
            grid-column: 1 / -1;
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

        .report-footer {
            text-align: center;
            padding: 20px;
            border-top: 2px solid var(--border-inner);
            color: var(--text-muted);
            font-size: 12px;
        }

        .report-footer p {
            margin: 4px 0;
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