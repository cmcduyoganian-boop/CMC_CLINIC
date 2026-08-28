<x-app-with-sidebar>
    <x-slot name="header">Vital Signs Report</x-slot>

    <div class="report-page">
        <!-- Header -->
        <div class="report-header">
            <div>
                <h1 class="report-title">Vital Signs Report</h1>
                <p class="report-subtitle">Abnormal readings and health alerts</p>
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

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="card danger">
                <h3>High Fever</h3>
                <p class="card-value">{{ $highFever }}</p>
                <p class="card-desc">(> 38°C)</p>
            </div>
            <div class="card warning">
                <h3>Low Temperature</h3>
                <p class="card-value">{{ $lowTemperature }}</p>
                <p class="card-desc">(< 36°C)</p>
            </div>
            <div class="card danger">
                <h3>Low Oxygen</h3>
                <p class="card-value">{{ $lowOxygen }}</p>
                <p class="card-desc">(< 95%)</p>
            </div>
        </div>

        <!-- Abnormal Readings Table -->
        <div class="table-card">
            <h2 class="table-title">Abnormal Vital Signs Readings</h2>
            <div class="table-wrapper">
                @if($abnormalReadings->isEmpty())
                    <p style="text-align: center; padding: 20px; color: #95a5a6;">No abnormal readings recorded</p>
                @else
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>PATIENT</th>
                                <th>CATEGORY</th>
                                <th>TEMPERATURE</th>
                                <th>SP02</th>
                                <th>BP</th>
                                <th>DIAGNOSIS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($abnormalReadings as $reading)
                                <tr>
                                    <td>{{ $reading->visit_date->format('M d, Y') }}</td>
                                    <td><strong>{{ $reading->patient->name }}</strong></td>
                                    <td>
                                        <span class="badge {{ $reading->patient->getCategoryBadgeClass() }}">
                                            {{ $reading->patient->getCategoryLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($reading->temperature)
                                            <span class="value {{ $reading->temperature > 38 || $reading->temperature < 36 ? 'abnormal' : '' }}">
                                                {{ $reading->temperature }}°C
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($reading->spo2)
                                            <span class="value {{ $reading->spo2 < 95 ? 'abnormal' : '' }}">
                                                {{ $reading->spo2 }}%
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{ $reading->bp_systolic && $reading->bp_diastolic ? $reading->bp_systolic . '/' . $reading->bp_diastolic : '-' }}
                                    </td>
                                    <td>{{ $reading->diagnosis ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="report-footer">
            <p>Report generated from CMC Clinic Management System</p>
            <p>{{ now()->format('F d, Y H:i A') }}</p>
        </div>
    </div>

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
            color: #3498db;
        }

        .card.danger .card-value {
            color: #e74c3c;
        }

        .card.warning .card-value {
            color: #f39c12;
        }

        .card-desc {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #95a5a6;
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

        .value {
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .value.abnormal {
            background: #fadbd8;
            color: #c0392b;
            font-weight: 700;
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

        @media print {
            .header-actions { display: none; }
        }
    </style>
</x-app-with-sidebar>