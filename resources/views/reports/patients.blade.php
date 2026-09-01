<x-app-with-sidebar>
    <x-slot name="header">Patient Report</x-slot>

    <div class="report-page">
        <!-- Header -->
        <div class="report-header">
            <div>
                <h1 class="report-title">Patient Report</h1>
                <p class="report-subtitle">Generated on {{ now()->format('F d, Y \a\t H:i A') }}</p>
            </div>
            <div class="header-actions">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ route('reports.download', 'patients') }}" class="btn btn-download">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="card">
                <h3>Total Patients</h3>
                <p class="card-value">{{ $totalPatients }}</p>
            </div>
            <div class="card">
                <h3>Students</h3>
                <p class="card-value">{{ $students }}</p>
            </div>
            <div class="card">
                <h3>Faculty</h3>
                <p class="card-value">{{ $faculty }}</p>
            </div>
            <div class="card">
                <h3>Staff</h3>
                <p class="card-value">{{ $staff }}</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-card">
                <h2 class="chart-title">Patients by Category</h2>
                <canvas id="categoryChart"></canvas>
            </div>

            <div class="chart-card">
                <h2 class="chart-title">Patient Distribution</h2>
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Patients Table -->
        <div class="table-card">
            <h2 class="table-title">All Patients</h2>
            <div class="table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NAME</th>
                            <th>CATEGORY</th>
                            <th>PROGRAM</th>
                            <th>YEAR/SECTION</th>
                            <th>PHONE</th>
                            <th>VISITS</th>
                            <th>LAST VISIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $index => $patient)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $patient->name }}</strong></td>
                                <td>
                                    <span class="badge {{ $patient->getCategoryBadgeClass() }}">
                                        {{ $patient->getCategoryLabel() }}
                                    </span>
                                </td>
                                <td>{{ $patient->program ?: '-' }}</td>
                                <td>{{ $patient->year_section ?? '-' }}</td>
                                <td>{{ $patient->phone ?? '-' }}</td>
                                <td>{{ $patient->clinic_visits_count }}</td>
                                <td>
                                    @if($patient->clinicVisits->first())
                                        {{ $patient->clinicVisits->first()->visit_date->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
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
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Students', 'Faculty', 'Staff'],
                datasets: [{
                    data: [{{ $students }}, {{ $faculty }}, {{ $staff }}],
                    backgroundColor: ['#3498db', '#9b59b6', '#27ae60'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    label: 'Patients',
                    data: [{{ $activePatients }}, {{ $inactivePatients }}],
                    backgroundColor: ['#27ae60', '#e74c3c']
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } },
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

        .btn-download {
            background: #27ae60;
            color: white;
        }

        .btn-download:hover {
            background: #229954;
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
            color: #3498db;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
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

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-student {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-faculty {
            background: #ede9fe;
            color: #6d28d9;
        }

        .badge-staff {
            background: #dcfce7;
            color: #15803d;
        }

        .report-footer {
            text-align: center;
            padding: 20px;
            border-top: 2px solid #e8ecf1;
            color: #95a5a6;
            font-size: 12px;
        }

        .report-footer p {
            margin: 4px 0;
        }

        @media print {
            .header-actions { display: none; }
            .report-page { gap: 16px; }
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

            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-with-sidebar>