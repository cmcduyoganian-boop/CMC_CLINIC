<x-app-with-sidebar>
    <x-slot name="header">Diagnosis Report</x-slot>

    <div class="report-page">
        <!-- Header -->
        <div class="report-header">
            <div>
                <h1 class="report-title">Diagnosis Report</h1>
                <p class="report-subtitle">Common diagnoses and conditions</p>
            </div>
            <div class="header-actions">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ route('reports.download', 'diagnosis') }}" class="btn btn-download">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Summary -->
        <div class="summary-cards">
            <div class="card">
                <h3>Unique Diagnoses</h3>
                <p class="card-value">{{ $totalUniqueDiagnoses }}</p>
            </div>
        </div>

        <!-- Chart -->
        <div class="chart-card">
            <h2 class="chart-title">Top 10 Diagnoses</h2>
            <div class="chart-canvas-wrap">
                <canvas id="diagnosisChart"></canvas>
            </div>
        </div>

        <!-- Diagnoses Table -->
        <div class="table-card">
            <h2 class="table-title">Diagnosis Breakdown</h2>
            <div class="table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DIAGNOSIS</th>
                            <th>CASES</th>
                            <th>PERCENTAGE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = $topDiagnoses->sum('count'); @endphp
                        @foreach($topDiagnoses as $index => $diag)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $diag->diagnosis }}</strong></td>
                                <td>{{ $diag->count }}</td>
                                <td>
                                    <div class="progress-bar">
                                        <div class="progress" style="width: {{ ($diag->count / $total) * 100 }}%"></div>
                                    </div>
                                    {{ number_format(($diag->count / $total) * 100, 1) }}%
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
        const ctx = document.getElementById('diagnosisChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topDiagnoses->pluck('diagnosis')) !!},
                datasets: [{
                    label: 'Cases',
                    data: {!! json_encode($topDiagnoses->pluck('count')) !!},
                    backgroundColor: '#38bdf8'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { color: '#94a3b8' },
                        grid: { color: '#e2e8f0' }
                    },
                    y: {
                        ticks: { color: '#94a3b8' },
                        grid: { color: '#e2e8f0' }
                    }
                },
                plugins: { legend: { display: false } }
            }
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
        }

        .card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            text-align: center;
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
            color: #e74c3c;
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

        .progress-bar {
            width: 100%;
            height: 20px;
            background: var(--bg-input);
            border-radius: 10px;
            overflow: hidden;
            margin-right: 12px;
        }

        .progress {
            height: 100%;
            background: linear-gradient(90deg, #38bdf8, #2563eb);
            transition: width 0.3s ease;
        }

        .report-footer {
            text-align: center;
            padding: 20px;
            border-top: 2px solid var(--border-inner);
            color: var(--text-muted);
            font-size: 12px;
        }

        @media print {
            .header-actions { display: none; }
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