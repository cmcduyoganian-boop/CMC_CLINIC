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
            <canvas id="diagnosisChart"></canvas>
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
                    backgroundColor: '#3498db'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: { x: { beginAtZero: true } },
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
            color: #2d3e50;
        }

        .report-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: #95a5a6;
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

        .btn-back {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-back:hover {
            background: #d4d9e0;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            text-align: center;
        }

        .card h3 {
            margin: 0;
            font-size: 12px;
            font-weight: 600;
            color: #95a5a6;
            text-transform: uppercase;
        }

        .card-value {
            margin: 12px 0 0 0;
            font-size: 32px;
            font-weight: 700;
            color: #e74c3c;
        }

        .chart-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .chart-title {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3e50;
            border-bottom: 2px solid #e8ecf1;
            padding-bottom: 12px;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .table-title {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3e50;
            border-bottom: 2px solid #e8ecf1;
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
            background: #f9fafb;
            font-weight: 700;
            color: #2d3e50;
            border-bottom: 2px solid #e8ecf1;
            text-transform: uppercase;
            font-size: 11px;
        }

        .report-table td {
            padding: 12px;
            border-bottom: 1px solid #e8ecf1;
            color: #2d3e50;
        }

        .report-table tr:hover {
            background: #f9fafb;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e8ecf1;
            border-radius: 10px;
            overflow: hidden;
            margin-right: 12px;
        }

        .progress {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2980b9);
            transition: width 0.3s ease;
        }

        .report-footer {
            text-align: center;
            padding: 20px;
            border-top: 2px solid #e8ecf1;
            color: #95a5a6;
            font-size: 12px;
        }

        @media print {
            .header-actions { display: none; }
        }
    </style>
</x-app-with-sidebar>