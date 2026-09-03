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
            color: #38bdf8;
        }

        .card.warning .card-value {
            color: #fbbf24;
        }

        .card.danger .card-value {
            color: #f87171;
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

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-good-stock {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .badge-low-stock {
            background: rgba(243, 156, 18, 0.15);
            color: #fbbf24;
        }

        .badge-out-of-stock {
            background: rgba(248, 113, 113, 0.15);
            color: #f87171;
        }

        .expired {
            color: #f87171;
            font-weight: 600;
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