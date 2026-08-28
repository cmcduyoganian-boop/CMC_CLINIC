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
        color: var(--text-body);
    }

    .btn-new-appointment {
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
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-new-appointment:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

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
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        display: flex;
        gap: 16px;
        align-items: center;
        transition: all 0.2s;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        transform: translateY(-2px);
    }

    .card-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
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

    .search-section {
        display: flex;
        gap: 12px;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        align-items: center;
    }

    .search-group {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 8px 12px 8px 36px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        background: var(--bg-input);
        color: var(--text-heading);
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
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        background: var(--bg-input);
        color: var(--text-heading);
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
        border-color: #38bdf8;
    }

    .table-container {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        overflow-x: auto;
        transition: opacity 0.15s;
    }

    .table-container.table-loading {
        opacity: 0.6;
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
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: white;
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

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .appointments-table thead th {
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

    .appointments-table tbody tr {
        border-bottom: 1px solid var(--border-inner);
        transition: background 0.15s;
    }

    .appointments-table tbody tr:hover {
        background: var(--bg-input);
    }

    .appointments-table tbody tr.upcoming {
        background: rgba(56,189,248,0.04);
    }

    .appointments-table td {
        padding: 12px 16px;
        color: var(--text-body);
        font-size: 13px;
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

    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-student {
        background: rgba(56,189,248,0.1);
        color: #38bdf8;
    }

    .badge-faculty {
        background: rgba(139,92,246,0.1);
        color: #8b5cf6;
    }

    .badge-staff {
        background: rgba(39,174,96,0.1);
        color: #27ae60;
    }

    .reason {
        font-size: 12px;
        color: var(--text-muted);
        max-width: 120px;
    }

    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-scheduled {
        background: rgba(56,189,248,0.1);
        color: #38bdf8;
    }

    .badge-completed {
        background: rgba(39,174,96,0.1);
        color: #27ae60;
    }

    .badge-no-show {
        background: rgba(243,156,18,0.1);
        color: #f39c12;
    }

    .badge-cancelled {
        background: rgba(127,140,141,0.1);
        color: #7f8c8d;
    }

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
        padding: 6px 8px;
        border-radius: 6px;
        transition: all 0.15s;
        font-size: 13px;
        text-decoration: none;
        color: var(--text-muted);
    }

    .btn-view:hover {
        background: rgba(56,189,248,0.1);
        color: #38bdf8;
    }

    .btn-edit:hover {
        background: rgba(243,156,18,0.1);
        color: #f39c12;
    }

    .btn-delete {
        color: var(--text-muted);
    }

    .btn-delete:hover {
        background: rgba(231,76,60,0.1);
        color: #e74c3c;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--border-inner);
    }

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