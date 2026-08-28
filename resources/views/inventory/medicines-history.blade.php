<x-app-with-sidebar>
    <x-slot name="header">Medicine History</x-slot>

    <div class="history-page">
        <!-- Header -->
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">{{ $medicine->name }}</h1>
                <p class="page-subtitle">Inventory History</p>
                <div class="medicine-info-badges">
                    <span class="badge {{ $medicine->getStatusBadgeClass() }}">
                        {{ $medicine->getStatusLabel() }}
                    </span>
                    <span class="info-badge">Current Stock: {{ $medicine->quantity }} {{ $medicine->unit }}</span>
                </div>
            </div>
            <a href="{{ route('medicines.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Medicine Summary Card -->
        <div class="summary-card">
            <div class="summary-item">
                <span class="summary-label">Current Quantity</span>
                <span class="summary-value">{{ $medicine->quantity }}</span>
                <span class="summary-unit">{{ $medicine->unit }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Minimum Stock</span>
                <span class="summary-value">{{ $medicine->minimum_stock }}</span>
                <span class="summary-unit">{{ $medicine->unit }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Storage Location</span>
                <span class="summary-value">{{ $medicine->storage_location ?? 'Not Set' }}</span>
            </div>
            @if($medicine->expiration_date)
                <div class="summary-item">
                    <span class="summary-label">Expiration Date</span>
                    <span class="summary-value {{ $medicine->isExpired() ? 'expired' : '' }}">
                        {{ $medicine->expiration_date->format('M d, Y') }}
                    </span>
                </div>
            @endif
        </div>

        <!-- History Table -->
        <div class="table-container">
            @if($logs->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <p>No inventory history yet</p>
                </div>
            @else
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>DATE & TIME</th>
                            <th>ACTION</th>
                            <th>QUANTITY</th>
                            <th>NOTES</th>
                            <th>RECORDED BY</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr class="log-row action-{{ $log->action }}">
                                <td class="date-time">
                                    {{ $log->created_at->format('M d, Y') }}<br>
                                    <small>{{ $log->created_at->format('H:i A') }}</small>
                                </td>
                                <td>
                                    <span class="action-badge badge-{{ $log->action }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="quantity">
                                    <span class="qty-value {{ in_array($log->action, ['received', 'adjusted']) ? 'positive' : 'negative' }}">
                                        {{ in_array($log->action, ['received', 'adjusted']) ? '+' : '-' }}{{ $log->quantity }}
                                    </span>
                                </td>
                                <td class="notes">
                                    {{ $log->notes ?? '-' }}
                                </td>
                                <td class="recorded-by">
                                    {{ $log->user ? $log->user->name : 'System' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="page-actions">
            <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-edit">
                <i class="fas fa-edit"></i> Edit Medicine
            </a>
            <a href="{{ route('medicines.index') }}" class="btn btn-back-full">
                <i class="fas fa-arrow-left"></i> Back to Inventory
            </a>
        </div>
    </div>

    <style>
        .history-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-left {
            flex: 1;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #2d3e50;
        }

        .page-subtitle {
            margin: 4px 0 12px 0;
            font-size: 13px;
            color: #95a5a6;
        }

        .medicine-info-badges {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
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

        .info-badge {
            background: #eef2f5;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #3498db;
        }

        .btn-back {
            background: #ecf0f1;
            color: #7f8c8d;
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

        .btn-back:hover {
            background: #d4d9e0;
        }

        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
        }

        .summary-item {
            background: #f9fafb;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            border-left: 4px solid #3498db;
        }

        .summary-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #95a5a6;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .summary-value {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: #2d3e50;
        }

        .summary-value.expired {
            color: #e74c3c;
        }

        .summary-unit {
            display: block;
            font-size: 11px;
            color: #95a5a6;
            margin-top: 4px;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .empty-state {
            padding: 60px 24px;
            text-align: center;
            color: #95a5a6;
        }

        .empty-state i {
            font-size: 48px;
            opacity: 0.3;
            display: block;
            margin-bottom: 16px;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .history-table th {
            padding: 14px 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .history-table tbody tr {
            border-bottom: 1px solid #e8ecf1;
            transition: all 0.2s;
        }

        .history-table tbody tr:hover {
            background: #f9fafb;
        }

        .history-table td {
            padding: 12px;
            color: #2d3e50;
        }

        .date-time {
            font-weight: 600;
            font-size: 11px;
        }

        .date-time small {
            color: #95a5a6;
        }

        .action-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-received {
            background: #d5f4e6;
            color: #27ae60;
        }

        .badge-used {
            background: #fadbd8;
            color: #c0392b;
        }

        .badge-adjusted {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-damaged {
            background: #ffeaa7;
            color: #d68910;
        }

        .badge-expired {
            background: #fadbd8;
            color: #c0392b;
        }

        .quantity {
            text-align: center;
            font-weight: 700;
        }

        .qty-value {
            padding: 4px 12px;
            border-radius: 4px;
            display: inline-block;
            font-size: 11px;
        }

        .qty-value.positive {
            background: #d5f4e6;
            color: #27ae60;
        }

        .qty-value.negative {
            background: #fadbd8;
            color: #c0392b;
        }

        .notes {
            font-size: 11px;
            color: #95a5a6;
            max-width: 150px;
        }

        .recorded-by {
            font-size: 11px;
            color: #95a5a6;
        }

        .pagination-wrapper {
            padding: 16px 24px;
            text-align: center;
        }

        .page-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
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

        .btn-edit {
            background: #3498db;
            color: white;
        }

        .btn-edit:hover {
            background: #2980b9;
        }

        .btn-back-full {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-back-full:hover {
            background: #d4d9e0;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
            }

            .summary-card {
                grid-template-columns: repeat(2, 1fr);
            }

            .history-table {
                font-size: 11px;
            }

            .history-table th,
            .history-table td {
                padding: 8px;
            }

            .page-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }

            .summary-card {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-with-sidebar>