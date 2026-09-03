<div class="appointments-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <h1 class="page-title">Appointments</h1>
            <p class="page-description">Schedule and manage clinic appointments</p>
        </div>
        <a href="{{ route('appointments.create') }}" class="btn-new-appointment">
            <i class="fas fa-plus"></i> Schedule Appointment
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Status Cards -->
    <div class="status-cards">
        <div class="card upcoming">
            <div class="card-icon">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="card-info">
                <h3>Upcoming</h3>
                <p class="card-value">{{ $upcoming }}</p>
            </div>
        </div>

        <div class="card completed">
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-info">
                <h3>Completed</h3>
                <p class="card-value">{{ $completed }}</p>
            </div>
        </div>

        <div class="card total">
            <div class="card-icon">
                <i class="fas fa-list"></i>
            </div>
            <div class="card-info">
                <h3>Total</h3>
                <p class="card-value">{{ $total }}</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="search-section">
        <div class="search-group">
            <input type="text" class="search-input" placeholder="Search by patient name..." wire:model.live.debounce.300ms="search">
            <i class="fas fa-search search-icon"></i>
        </div>
        <select class="filter-select" wire:model.live="statusFilter">
            <option value="">All Status</option>
            <option value="scheduled">Scheduled</option>
            <option value="completed">Completed</option>
            <option value="no-show">No-show</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <!-- Appointments Table -->
    <div class="table-container" wire:loading.class="table-loading">
        @if($appointments->isEmpty())
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                @if($search || $statusFilter)
                    <p>No appointments found matching your search/filter.</p>
                @else
                    <p>No appointments scheduled</p>
                    <a href="{{ route('appointments.create') }}" class="btn-empty">
                        Schedule First Appointment
                    </a>
                @endif
            </div>
        @else
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>DATE & TIME</th>
                        <th>PATIENT NAME</th>
                        <th>CATEGORY</th>
                        <th>REASON</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr class="appointment-row {{ $appointment->isUpcoming() ? 'upcoming' : '' }}" wire:key="appointment-{{ $appointment->id }}">
                            <td class="date-time">
                                <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong><br>
                                {{ date('h:i A', strtotime($appointment->appointment_time)) }}
                            </td>
                            <td class="patient-name">{{ $appointment->patient->name }}</td>
                            <td>
                                <span class="badge {{ $appointment->patient->getCategoryBadgeClass() }}">
                                    {{ $appointment->patient->getCategoryLabel() }}
                                </span>
                            </td>
                            <td class="reason">{{ $appointment->reason ?? '-' }}</td>
                            <td>
                                <span class="status-badge {{ $appointment->getStatusBadgeClass() }}">
                                    {{ $appointment->getStatusLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn-delete"
                                        title="Delete"
                                        wire:click="deleteAppointment({{ $appointment->id }})"
                                        wire:confirm="Delete this appointment for {{ $appointment->patient->name }}? This cannot be undone."
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .appointments-page {
        display: flex;
        flex-direction: column;
        gap: 20px;
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

    .btn-new-appointment {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-new-appointment:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* ── Alert ── */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .alert-success {
        background: rgba(39,174,96,0.1);
        color: #27ae60;
        border: 1px solid rgba(39,174,96,0.2);
    }

    /* ── Status summary cards ── */
    .status-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }

    .card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        display: flex;
        gap: 16px;
        align-items: center;
        transition: all 0.2s;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    .card-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        flex-shrink: 0;
    }

    .card.upcoming .card-icon {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
    }

    .card.completed .card-icon {
        background: #27ae60;
    }

    .card.total .card-icon {
        background: #8b5cf6;
    }

    .card-info h3 {
        margin: 0;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-value {
        margin: 6px 0 0 0;
        font-size: 26px;
        font-weight: 700;
        color: var(--text-heading);
    }

    /* ── Search / Filter bar ── */
    .search-section {
        display: flex;
        gap: 12px;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        align-items: center;
    }

    .search-group {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 9px 14px 9px 36px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        background: var(--bg-input);
        color: var(--text-heading);
        transition: border-color 0.15s;
    }

    .search-input:focus {
        outline: none;
        border-color: #38bdf8;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .filter-select {
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        background: var(--bg-input);
        color: var(--text-heading);
        cursor: pointer;
        transition: border-color 0.15s;
    }

    .filter-select:focus {
        outline: none;
        border-color: #38bdf8;
    }

    /* ── Table container ── */
    .table-container {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow-x: auto;
        transition: opacity 0.15s;
    }

    .table-container.table-loading {
        opacity: 0.6;
    }

    /* ── Empty state ── */
    .empty-state {
        padding: 48px 24px;
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
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        transition: all 0.2s;
    }

    .btn-empty:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* ── Table ── */
    .appointments-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .appointments-table thead th {
        background: linear-gradient(135deg, #2980b9, #1a6ea8);
        color: #fff;
        padding: 13px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .appointments-table thead th:first-child {
        border-radius: 11px 0 0 0;
    }

    .appointments-table thead th:last-child {
        border-radius: 0 11px 0 0;
    }

    .appointments-table tbody tr {
        border-bottom: 1px solid var(--border-inner);
        transition: background 0.15s;
    }

    .appointments-table tbody tr:last-child {
        border-bottom: none;
    }

    .appointments-table tbody tr:hover {
        background: var(--bg-input);
    }

    .appointments-table tbody tr.upcoming {
        background: rgba(56,189,248,0.04);
    }

    .appointments-table tbody tr.upcoming:hover {
        background: var(--bg-input);
    }

    .appointments-table td {
        padding: 12px 16px;
        color: var(--text-heading);
        font-size: 13px;
        vertical-align: middle;
    }

    .date-time {
        font-weight: 600;
        font-size: 12px;
        color: var(--text-heading);
    }

    .patient-name {
        font-weight: 700;
        color: #38bdf8;
    }

    /* ── Category badges ── */
    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-student { background: rgba(56,189,248,0.1); color: #38bdf8; }
    .badge-faculty { background: rgba(139,92,246,0.1); color: #8b5cf6; }
    .badge-staff { background: rgba(39,174,96,0.1); color: #27ae60; }

    .reason {
        font-size: 12px;
        color: var(--text-muted);
        max-width: 120px;
    }

    /* ── Status badges (pill style) ── */
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-scheduled { background: rgba(56,189,248,0.1); color: #38bdf8; }
    .badge-pending { background: rgba(56,189,248,0.1); color: #38bdf8; }
    .badge-completed { background: rgba(39,174,96,0.1); color: #27ae60; }
    .badge-no-show { background: rgba(243,156,18,0.1); color: #f39c12; }
    .badge-cancelled { background: rgba(127,140,141,0.1); color: #7f8c8d; }

    /* ── Action buttons (icon-only) ── */
    .action-buttons {
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .btn-view,
    .btn-edit,
    .btn-delete {
        border: none;
        background: transparent;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: background 0.15s, color 0.15s;
        font-size: 13px;
        text-decoration: none;
    }

    .btn-view { color: #2980b9; }
    .btn-view:hover { background: rgba(41,128,185,0.12); color: #1a6ea8; }

    .btn-edit { color: #f39c12; }
    .btn-edit:hover { background: rgba(243,156,18,0.12); color: #d68910; }

    .btn-delete { color: #e74c3c; }
    .btn-delete:hover { background: rgba(231,76,60,0.12); color: #c0392b; }

    /* ── Pagination ── */
    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--border-inner);
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 16px;
        }
        .btn-new-appointment {
            width: 100%;
            justify-content: center;
        }

        .search-section {
            flex-direction: column;
        }

        .appointments-table {
            font-size: 11px;
        }

        .appointments-table thead th,
        .appointments-table td {
            padding: 8px;
        }
    }
</style>