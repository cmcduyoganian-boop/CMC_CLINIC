<x-app-with-sidebar>
    <x-slot name="header">Clinic Visits</x-slot>

    <div class="clinic-visit-list-page">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">Clinic Visit Records</h1>
                <p class="page-description">View and manage all clinic visit records</p>
            </div>
            <a href="{{ route('clinic-visit.create') }}" class="btn-new-visit">
                <i class="fas fa-plus"></i> New Visit
            </a>
        </div>

        <!-- Clinic Visits Table -->
        <div class="table-container">
            <table class="visits-table">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>FULL NAME</th>
                        <th>YEAR & SECTION</th>
                        <th>AGE</th>
                        <th colspan="8">VITAL SIGNS</th>
                        <th>COMPLAINTS</th>
                        <th>DIAGNOSIS</th>
                        <th>MANAGEMENT</th>
                        <th>ACTIONS</th>
                    </tr>
                    <tr class="vital-signs-header">
                        <th colspan="4"></th>
                        <th>T°</th>
                        <th>PR</th>
                        <th>RR</th>
                        <th>BP</th>
                        <th>HT</th>
                        <th>WT</th>
                        <th>BMI</th>
                        <th>SpO2</th>
                        <th colspan="4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $visit)
                        <tr class="visit-row">
                            <td>{{ $visit->visit_date->format('m/d/Y') }}</td>
                            <td class="patient-name">{{ $visit->patient->name ?? 'N/A' }}</td>
                            <td class="year-section">{{ $visit->patient->year_section ?? 'N/A' }}</td>
                            <td class="center">{{ $visit->patient->age ?? 'N/A' }}</td>
                            <td class="vital-sign">{{ $visit->temperature ? $visit->temperature . '°C' : '-' }}</td>
                            <td class="vital-sign">{{ $visit->pulse_rate ?: '-' }}</td>
                            <td class="vital-sign">{{ $visit->respiratory_rate ?: '-' }}</td>
                            <td class="vital-sign">{{ $visit->bp_systolic && $visit->bp_diastolic ? $visit->bp_systolic . '/' . $visit->bp_diastolic : '-' }}</td>
                            <td class="vital-sign">{{ $visit->height ? $visit->height . 'cm' : '-' }}</td>
                            <td class="vital-sign">{{ $visit->weight ? $visit->weight . 'kg' : '-' }}</td>
                            <td class="vital-sign">{{ $visit->getBMI() ?: '-' }}</td>
                            <td class="vital-sign">{{ $visit->spo2 ? $visit->spo2 . '%' : '-' }}</td>
                            <td class="text-small">{{ $visit->complaints ?: '-' }}</td>
                            <td class="text-small">{{ $visit->diagnosis ?: '-' }}</td>
                            <td class="text-small">{{ $visit->management ?: '-' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('clinic-visit.show', $visit->id) }}" class="btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clinic-visit.edit', $visit->id) }}" class="btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('clinic-visit.destroy', $visit->id) }}" style="display:inline;" onsubmit="return confirm('Delete this visit record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16" style="text-align:center;padding:40px;color:var(--text-muted);">
                                No clinic visits recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($visits->hasPages())
            <div class="pagination-section">
                <p class="pagination-info">
                    Showing {{ $visits->firstItem() ?? 0 }} to {{ $visits->lastItem() ?? 0 }} of {{ $visits->total() }} visits
                </p>
                <div class="pagination">
                    {{ $visits->links() }}
                </div>
            </div>
        @endif
    </div>

    <style>
        .clinic-visit-list-page {
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
            color: var(--text-heading);
        }

        .page-description {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .btn-new-visit {
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-new-visit:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .table-container {
            background: var(--bg-card);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .visits-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .visits-table thead tr:first-child th {
            padding: 14px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: 1px solid #2980b9;
        }

        .vital-signs-header th {
            background: #34495e !important;
            padding: 10px 6px !important;
            font-size: 10px !important;
        }

        .visits-table tbody tr {
            border-bottom: 1px solid var(--border-inner);
            transition: all 0.2s;
        }

        .visits-table tbody tr:hover {
            background: var(--bg-input);
        }

        .visits-table td {
            padding: 12px 10px;
            color: var(--text-heading);
            border-right: 1px solid var(--border-inner);
        }

        .visits-table td:last-child {
            border-right: none;
        }

        .patient-name {
            font-weight: 700;
            color: #3498db;
            min-width: 120px;
        }

        .year-section {
            font-weight: 600;
            color: #9b59b6;
            min-width: 100px;
        }

        .center {
            text-align: center;
        }

        .vital-sign {
            text-align: center;
            font-weight: 600;
            color: var(--text-heading);
            min-width: 45px;
        }

        .text-small {
            font-size: 11px;
            color: var(--text-muted);
            max-width: 80px;
            white-space: normal;
        }

        .action-buttons {
            display: flex;
            gap: 4px;
            justify-content: center;
        }

        .btn-view,
        .btn-edit,
        .btn-delete {
            border: none;
            background: transparent;
            color: #3498db;
            cursor: pointer;
            padding: 6px;
            border-radius: 4px;
            transition: all 0.2s;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-view:hover {
            background: rgba(52, 152, 219, 0.1);
            color: #2980b9;
        }

        .btn-edit:hover {
            background: rgba(241, 196, 15, 0.1);
            color: #f39c12;
        }

        .btn-delete:hover {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .pagination-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-card);
            border-radius: 10px;
            padding: 16px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .pagination-info {
            margin: 0;
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .pagination {
            display: flex;
            gap: 8px;
        }

        .pagination a,
        .pagination span {
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid var(--border-card);
            background: var(--bg-card);
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .pagination .active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 1024px) {
            .visits-table {
                font-size: 11px;
            }

            .visits-table td,
            .visits-table th {
                padding: 10px 8px;
            }

            .text-small {
                max-width: 60px;
                font-size: 10px;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
            }

            .btn-new-visit {
                width: 100%;
                justify-content: center;
            }

            .table-container {
                overflow-x: scroll;
            }

            .visits-table {
                font-size: 10px;
                min-width: 1200px;
            }

            .pagination-section {
                flex-direction: column;
                gap: 12px;
            }

            .pagination {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }

            .visits-table {
                font-size: 9px;
                min-width: 1400px;
            }

            .visits-table th,
            .visits-table td {
                padding: 8px 4px;
            }

            .action-buttons {
                gap: 2px;
            }

            .btn-view,
            .btn-edit,
            .btn-delete {
                padding: 4px;
                font-size: 12px;
            }
        }
    </style>
</x-app-with-sidebar>
