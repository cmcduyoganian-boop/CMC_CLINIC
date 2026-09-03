<div class="patients-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <h1 class="page-title">Patient Records</h1>
            <p class="page-description">Manage and view all patient records and medical history</p>
        </div>
        <a href="{{ route('patients.create') }}" class="btn-new-patient">
            <i class="fas fa-plus"></i> Add New Patient
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="search-section">
        <select class="filter-select" wire:model.live="category">
            <option value="">All Categories</option>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
            <option value="staff">Staff</option>
        </select>
    </div>

    <!-- Patients Table -->
    <div class="table-container" wire:loading.class="table-loading">
        @if($patients->isEmpty())
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                @if($search || $category)
                    <p>No patients found matching your search/filter.</p>
                @else
                    <p>No patients found.</p>
                    <a href="{{ route('patients.create') }}" class="btn-empty">
                        Add First Patient
                    </a>
                @endif
            </div>
        @else
            <table class="patients-table">
                <thead>
                    <tr>
                        <th>PATIENT NAME</th>
                        <th>CATEGORY</th>
                        <th>PROGRAM</th>
                        <th>YEAR & SECTION</th>
                        <th>PHONE</th>
                        <th>CLINIC VISITS</th>
                        <th>LAST VISIT</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        <tr class="patient-row" wire:key="patient-{{ $patient->id }}" onclick="window.location.href='{{ route('patients.show', $patient->id) }}';" style="cursor: pointer;">
                            <td>
                                <div class="patient-info">
                                    <div class="patient-avatar">
                                        {{ substr($patient->name, 0, 1) }}
                                    </div>
                                    <span class="patient-name">{{ $patient->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $patient->getCategoryBadgeClass() }}">
                                    {{ $patient->getCategoryLabel() }}
                                </span>
                            </td>
                            <td>{{ $patient->program ?: '-' }}</td>
                            <td>{{ $patient->year_section ?? '-' }}</td>
                            <td class="phone-masked">{{ $patient->phone ? substr($patient->phone, 0, 3) . '-****-' . substr($patient->phone, -3) : '-' }}</td>
                            <td>
                                <span class="visit-count">
                                    <i class="fas fa-stethoscope"></i>
                                    {{ $patient->clinic_visits_count }}
                                </span>
                            </td>
                            <td>
                                @if($patient->clinicVisits->first())
                                    {{ $patient->clinicVisits->first()->visit_date->format('M d, Y') }}
                                @else
                                    <span class="text-muted">No visits</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons" onclick="event.stopPropagation();">
                                    <a href="{{ route('patients.show', $patient->id) }}" class="btn-view" title="View History">
                                        <i class="fas fa-history"></i>
                                    </a>
                                    <a href="{{ route('clinic-visit.create') }}?patient={{ $patient->id }}" class="btn-add" title="New Visit">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn-edit" title="Edit Patient">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="btn-delete"
                                        title="Delete Patient"
                                        wire:click="deletePatient({{ $patient->id }})"
                                        wire:confirm="Delete {{ $patient->name }}? This will also permanently delete all of their clinic visits and appointments. This cannot be undone."
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
                {{ $patients->links() }}
            </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <div class="stats-section">
        <div class="stat-item">
            <h3>Total Patients</h3>
            <p class="stat-value">{{ $totalPatients }}</p>
        </div>

                <div class="stat-item">
            <h3>Students</h3>
            <p class="stat-value">{{ $studentCount }}</p>
        </div>
        <div class="stat-item">
            <h3>Faculty</h3>
            <p class="stat-value">{{ $facultyCount }}</p>
        </div>
        <div class="stat-item">
            <h3>Staff</h3>
            <p class="stat-value">{{ $staffCount }}</p>
        </div>
    </div>
</div>

<style>
    .patients-page {
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

    .btn-new-patient {
        background: linear-gradient(135deg, #2980b9, #1a6ea8);
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

    .btn-new-patient:hover {
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
        background: rgba(39, 174, 96, 0.1);
        color: #1e8449;
        border: 1px solid rgba(39, 174, 96, 0.25);
    }

    /* ── Search / filter bar ── */
    .search-section {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        padding: 16px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
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
    }

    .search-input:focus {
        outline: none;
        border-color: #2980b9;
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
    }

    .filter-select:focus {
        outline: none;
        border-color: #2980b9;
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
        background: linear-gradient(135deg, #2980b9, #1a6ea8);
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
        transform: translateY(-1px);
    }

    /* ── Table ── */
    .patients-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .patients-table thead th {
        padding: 13px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        background: linear-gradient(135deg, #2980b9, #1a6ea8);
        color: #fff;
    }

    .patients-table thead th:last-child {
        border-right: none;
    }

    .patients-table tbody tr {
        border-bottom: 1px solid var(--border-inner);
        transition: background 0.15s;
    }

    .patients-table tbody tr:last-child {
        border-bottom: none;
    }

    .patients-table tbody tr:hover {
        background: var(--bg-input);
    }

    .patients-table td {
        padding: 12px 16px;
        color: var(--text-heading);
        font-size: 13px;
        vertical-align: middle;
    }

    .patients-table td:last-child {
        border-right: none;
    }

    /* ── Patient info cell ── */
    .patient-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .patient-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2980b9, #1a6ea8);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
    }

    .patient-name {
        font-weight: 700;
        color: var(--text-heading);
    }

    /* ── Badges ── */
    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-student {
        background: rgba(41, 128, 185, 0.12);
        color: #1a6ea8;
    }

    .badge-faculty {
        background: rgba(139, 92, 246, 0.12);
        color: #7c3aed;
    }

    .badge-staff {
        background: rgba(39, 174, 96, 0.12);
        color: #1e8449;
    }

    /* ── Phone / muted ── */
    .phone-masked {
        font-family: 'Courier New', monospace;
        color: var(--text-muted);
    }

    /* ── Visit count pill ── */
    .visit-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(41, 128, 185, 0.12);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: #2980b9;
    }

    /* ── Text muted helper ── */
    .text-muted {
        color: var(--text-muted);
        font-style: italic;
    }

    /* ── Action buttons ── */
    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .btn-view,
    .btn-add,
    .btn-edit,
    .btn-delete {
        border: none;
        background: transparent;
        cursor: pointer;
        padding: 6px 8px;
        border-radius: 6px;
        transition: background 0.15s, color 0.15s;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* View — blue */
    .btn-view {
        color: #2980b9;
    }
    .btn-view:hover {
        background: rgba(41, 128, 185, 0.12);
        color: #1a6ea8;
    }

    /* Add / new visit — green */
    .btn-add {
        color: #27ae60;
    }
    .btn-add:hover {
        background: rgba(39, 174, 96, 0.12);
        color: #1e8449;
    }

    /* Edit — orange */
    .btn-edit {
        color: #e67e22;
    }
    .btn-edit:hover {
        background: rgba(230, 126, 34, 0.12);
        color: #ca6f1e;
    }

    /* Delete — red */
    .btn-delete {
        color: #e74c3c;
    }
    .btn-delete:hover {
        background: rgba(231, 76, 60, 0.12);
        color: #c0392b;
    }

    /* ── Pagination ── */
    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--border-inner);
        text-align: center;
    }

    /* ── Stats cards ── */
    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }

    .stat-item {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .stat-item h3 {
        margin: 0;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        margin: 12px 0 0 0;
        font-size: 28px;
        font-weight: 700;
        color: var(--text-heading);
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 16px;
        }

        .btn-new-patient {
            width: 100%;
            justify-content: center;
        }

        .search-section {
            flex-direction: column;
        }

        .patients-table {
            font-size: 11px;
        }

        .patients-table th,
        .patients-table td {
            padding: 8px;
        }

        .patient-avatar {
            width: 30px;
            height: 30px;
            font-size: 11px;
        }
    }
</style>
