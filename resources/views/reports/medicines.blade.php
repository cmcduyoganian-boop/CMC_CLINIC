<x-app-with-sidebar>
    <x-slot name="header">Medicine Inventory Report</x-slot>

    <div class="report-page">
        <!-- Header -->
        <div class="report-header">
            <div>
                <h1 class="report-title">Medicine Inventory Report</h1>
                <p class="report-subtitle">Stock levels and inventory status</p>
            </div>
            <div class="header-actions">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ route('reports.download', 'medicines') }}" class="btn btn-download">
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
                <h3>Total Medicines</h3>
                <p class="card-value">{{ $totalMedicines }}</p>
            </div>
            <div class="card warning">
                <h3>Low Stock</h3>
                <p class="card-value">{{ $lowStock }}</p>
            </div>
            <div class="card danger">
                <h3>Out of Stock</h3>
                <p class="card-value">{{ $outOfStock }}</p>
            </div>
        </div>

        <!-- Medicines Table -->
        <div class="table-card">
            <h2 class="table-title">All Medicines</h2>
            <div class="table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>MEDICINE NAME</th>
                            <th>QUANTITY</th>
                            <th>UNIT</th>
                            <th>MIN STOCK</th>
                            <th>STATUS</th>
                            <th>EXPIRATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicines as $index => $med)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $med->name }}</strong></td>
                                <td>{{ $med->quantity }}</td>
                                <td>{{ $med->unit }}</td>
                                <td>{{ $med->minimum_stock }}</td>
                                <td>
                                    <span class="status-badge {{ $med->getStatusBadgeClass() }}">
                                        {{ $med->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    @if($med->expiration_date)
                                        <span class="{{ $med->isExpired() ? 'expired' : '' }}">
                                            {{ $med->expiration_date->format('M d, Y') }}
                                        </span>
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

        .card.warning .card-value {
            color: #f39c12;
        }

        .card.danger .card-value {
            color: #e74c3c;
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

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-good-stock {
            background: #d5f4e6;
            color: #27ae60;
        }

        .badge-low-stock {
            background: #ffeaa7;
            color: #d68910;
        }

        .badge-out-of-stock {
            background: #fadbd8;
            color: #c0392b;
        }

        .expired {
            color: #e74c3c;
            font-weight: 600;
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