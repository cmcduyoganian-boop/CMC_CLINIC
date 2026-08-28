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
        color: var(--text-body);
    }

    .btn-new-patient {
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

    .btn-new-patient:hover {
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
        background: rgba(39, 174, 96, 0.1);
        color: #27ae60;
        border: 1px solid rgba(39, 174, 96, 0.2);
    }

    .search-section {
        display: flex;
        justify-content: flex-end;
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
        transform: translateY(-1px);
    }

    .patients-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .patients-table th {
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

    .patients-table tbody tr {
        border-bottom: 1px solid var(--border-inner);
        transition: all 0.15s;
    }

    .patients-table tbody tr:hover {
        background: var(--bg-input);
    }

    .patients-table td {
        padding: 12px 16px;
        color: var(--text-body);
        font-size: 13px;
    }

    .patient-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .patient-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: white;
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

    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-student {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
    }

    .badge-faculty {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }

    .badge-staff {
        background: rgba(39, 174, 96, 0.1);
        color: #27ae60;
    }

    .phone-masked {
        font-family: 'Courier New', monospace;
        color: var(--text-muted);
    }

    .visit-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(56, 189, 248, 0.1);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: #38bdf8;
    }

    .text-muted {
        color: var(--text-muted);
        font-style: italic;
    }

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
        color: var(--text-muted);
        cursor: pointer;
        padding: 6px 8px;
        border-radius: 6px;
        transition: all 0.15s;
        font-size: 13px;
        text-decoration: none;
    }

    .btn-view:hover {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
    }

    .btn-add:hover {
        background: rgba(39, 174, 96, 0.1);
        color: #27ae60;
    }

    .btn-edit:hover {
        background: rgba(243, 156, 18, 0.1);
        color: #f39c12;
    }

    .btn-delete {
        color: #e74c3c;
    }

    .btn-delete:hover {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--border-inner);
        text-align: center;
    }

    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }

    .stat-item {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 16px 20px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
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
