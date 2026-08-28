<x-app-with-sidebar>
    <x-slot name="header">Clinic Visits</x-slot>

    <div class="clinic-visit-page">
        <!-- Header -->
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">Clinic Visit Records</h1>
                <p class="page-description">View and manage all clinic visit records</p>
            </div>
            <a href="{{ route('clinic-visit.create') }}" class="btn-new-visit">
                <i class="fas fa-plus"></i> New Visit
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Visits Table -->
        <div class="table-container">
            @if($visits->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No clinic visits recorded yet.</p>
                    <a href="{{ route('clinic-visit.create') }}" class="btn-empty">
                        Record First Visit
                    </a>
                </div>
            @else
                <table class="visits-table">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>PATIENT NAME</th>
                            <th>CATEGORY</th>
                            <th>YEAR & SECTION</th>
                            <th>COMPLAINTS</th>
                            <th>DIAGNOSIS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visits as $visit)
                            <tr class="visit-row">
                                <td>{{ $visit->visit_date ? date('m/d/Y', strtotime($visit->visit_date)) : '-' }}</td>
                                <td class="patient-name">{{ $visit->patient->name }}</td>
                                <td>
                                    <span class="badge {{ $visit->patient->getCategoryBadgeClass() }}">
                                        {{ $visit->patient->getCategoryLabel() }}
                                    </span>
                                </td>
                                <td class="year-section">{{ $visit->patient->year_section ?? '-' }}</td>
                                <td class="text-small">{{ Str::limit($visit->complaints, 40) }}</td>
                                <td class="text-small">{{ Str::limit($visit->diagnosis, 40) }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('clinic-visit.show', $visit->id) }}" class="btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (auth()->user()->role !== 'clinic_staff')
                                            <a href="{{ route('clinic-visit.edit', $visit->id) }}" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $visits->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .clinic-visit-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-left { flex: 1; }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .page-description {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: var(--text-body);
        }

        .btn-new-visit {
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-new-visit:hover { opacity: 0.9; transform: translateY(-2px); }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .alert-success {
            background: rgba(39, 174, 96, 0.1);
            color: #27ae60;
            border: 1px solid rgba(39, 174, 96, 0.2);
        }

        .table-container {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            overflow-x: auto;
        }

        .empty-state {
            padding: 60px 24px;
            text-align: center;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 48px;
            opacity: 0.3;
            display: block;
            margin-bottom: 16px;
        }

        .empty-state p {
            margin: 0 0 16px 0;
            font-size: 14px;
        }

        .btn-empty {
            background: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.2);
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-empty:hover { background: rgba(56, 189, 248, 0.2); }

        .visits-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .visits-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: transparent;
            border-bottom: 2px solid var(--border-inner);
        }

        .visits-table tbody tr {
            border-bottom: 1px solid var(--border-inner);
            transition: background 0.15s;
        }

        .visits-table tbody tr:hover { background: var(--bg-input); }

        .visits-table td {
            padding: 12px 16px;
            color: var(--text-body);
        }

        .patient-name {
            font-weight: 700;
            color: var(--text-heading);
        }

        .year-section {
            font-weight: 600;
            color: var(--text-body);
        }

        .text-small {
            font-size: 12px;
            color: var(--text-body);
            max-width: 150px;
            white-space: normal;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-student { background: rgba(56,189,248,0.1);  color: #38bdf8; }
        .badge-faculty { background: rgba(139,92,246,0.1);  color: #8b5cf6; }
        .badge-staff   { background: rgba(39,174,96,0.1);   color: #27ae60; }
        .badge-gray    { background: rgba(127,140,141,0.1); color: #7f8c8d; }

        .action-buttons { display: flex; gap: 6px; }

        .btn-view,
        .btn-edit {
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 6px;
            transition: all 0.15s;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-view { color: #38bdf8; }
        .btn-view:hover { background: rgba(56,189,248,0.1); }

        .btn-edit { color: #f39c12; }
        .btn-edit:hover { background: rgba(243,156,18,0.1); }

        .pagination-wrapper {
            padding: 16px 24px;
            border-top: 1px solid var(--border-inner);
            text-align: center;
        }

        @media (max-width: 768px) {
            .page-header { flex-direction: column; gap: 16px; }
            .btn-new-visit { width: 100%; justify-content: center; }
            .visits-table { font-size: 11px; }
            .visits-table th, .visits-table td { padding: 8px; }
        }
    </style>
</x-app-with-sidebar>
