<div class="users-container">
    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h1>User Management</h1>
            <p>Manage clinic system users and approvals</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Create User
        </a>
    </div>

    <!-- ALERTS -->
    @if ($flashMessage)
        <div class="alert alert-{{ $flashType === 'danger' ? 'error' : 'success' }}">
            <i class="fas fa-{{ $flashType === 'danger' ? 'exclamation-circle' : 'check-circle' }}"></i>
            {{ $flashMessage }}
            <button type="button" wire:click="dismissFlash" style="margin-left:auto;background:none;border:none;cursor:pointer;font-size:16px;color:inherit;">&times;</button>
        </div>
    @endif

    <!-- RESET PASSWORD RESULT -->
    @if ($showPasswordResult)
        <div class="alert alert-success" style="flex-direction:column;align-items:flex-start;gap:8px;">
            <strong><i class="fas fa-key"></i> New password for {{ $resultUsername }}:</strong>
            <div style="background:white;padding:10px 14px;border-radius:6px;font-family:monospace;font-size:15px;font-weight:700;letter-spacing:1px;">
                {{ $resultPassword }}
            </div>
            <small>Share this password securely with {{ $resultEmail }}. They will be asked to change it on next login.</small>
        </div>
    @endif

    <!-- STATS CARDS -->
    <div class="stats-grid">
        <button type="button" wire:click="$set('statusFilter', '')" class="stat-card {{ $statusFilter === '' ? 'active' : '' }}" style="border:none;text-align:left;width:100%;cursor:pointer;">
            <div class="stat-icon total">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </button>

        <button type="button" wire:click="$set('statusFilter', 'pending')" class="stat-card {{ $statusFilter === 'pending' ? 'active' : '' }}" style="border:none;text-align:left;width:100%;cursor:pointer;">
            <div class="stat-icon pending">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
                <div class="stat-label">Pending Approval</div>
            </div>
        </button>

        <button type="button" wire:click="$set('statusFilter', 'approved')" class="stat-card {{ $statusFilter === 'approved' ? 'active' : '' }}" style="border:none;text-align:left;width:100%;cursor:pointer;">
            <div class="stat-icon approved">
                <i class="fas fa-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['approved'] ?? 0 }}</div>
                <div class="stat-label">Approved</div>
            </div>
        </button>

        <button type="button" wire:click="$set('statusFilter', 'disabled')" class="stat-card {{ $statusFilter === 'disabled' ? 'active' : '' }}" style="border:none;text-align:left;width:100%;cursor:pointer;">
            <div class="stat-icon disabled">
                <i class="fas fa-ban"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['disabled'] ?? 0 }}</div>
                <div class="stat-label">Disabled</div>
            </div>
        </button>
    </div>

    <!-- ROLE BREAKDOWN -->
    <div class="role-breakdown">
        <h3>Users by Role</h3>
        <div class="breakdown-grid">
            <div class="breakdown-item">
                <span class="badge badge-blue">{{ $stats['students'] ?? 0 }}</span>
                Students
            </div>
            <div class="breakdown-item">
                <span class="badge badge-green">{{ $stats['faculty'] ?? 0 }}</span>
                Faculty
            </div>
            <div class="breakdown-item">
                <span class="badge badge-orange">{{ $stats['staff'] ?? 0 }}</span>
                Staff
            </div>
            <div class="breakdown-item">
                <span class="badge badge-purple">{{ $stats['clinic_nurse'] ?? 0 }}</span>
                Clinic Nurses
            </div>
            <div class="breakdown-item">
                <span class="badge badge-red">{{ $stats['clinic_staff'] ?? 0 }}</span>
                Clinic Staff
            </div>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="filters-card">
        <div class="filters-form">
            <input type="text" class="filter-select" style="flex:1;" placeholder="Search by name, username, or email..." wire:model.live.debounce.300ms="search">
            <select class="filter-select" wire:model.live="statusFilter">
                <option value="">-- All Users --</option>
                <option value="pending">Pending Approval</option>
                <option value="approved">Approved</option>
                <option value="disabled">Disabled</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    @if ($pendingRegistrations->isNotEmpty() && (!$statusFilter || $statusFilter === 'pending'))
        <div class="users-table-card pending-registrations-card">
            <div class="pending-header">
                <strong><i class="fas fa-envelope"></i> Pending Registrations</strong>
                <span>Waiting for email OTP verification</span>
            </div>
            <div class="users-table-wrapper">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingRegistrations as $pending)
                            <tr wire:key="pending-registration-{{ $pending->id }}">
                                <td>{{ $pending->name }}</td>
                                <td>{{ $pending->username }}</td>
                                <td>{{ $pending->email }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $pending->role)) }}</td>
                                <td><span class="badge badge-warning">OTP Pending</span></td>
                                <td>
                                    <button type="button" class="btn-action reject" title="Deny"
                                            wire:click="denyPendingRegistration({{ $pending->id }})"
                                            wire:confirm="Deny {{ $pending->name }}'s registration?">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- USERS TABLE -->
    <div class="users-table-card" wire:loading.class="table-loading">
        @if ($users->count() > 0)
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge badge-info">{{ $user->getRoleLabel() }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $user->getStatusBadgeClass() }}">
                                    {{ $user->getStatusLabel() }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- View -->
                                    <a href="{{ route('users.show', $user->id) }}" class="btn-action" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn-action" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Approve (if pending) -->
                                    @if ($user->approval_status === 'pending')
                                        <button type="button" class="btn-action approve" title="Confirm"
                                            wire:click="approveUser({{ $user->id }})"
                                            wire:confirm="Approve {{ $user->name }}'s account?">
                                            <i class="fas fa-check"></i>
                                        </button>

                                        <button type="button" class="btn-action reject" title="Deny"
                                            wire:click="rejectUser({{ $user->id }})"
                                            wire:confirm="Reject {{ $user->name }}'s account?">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    <!-- Disable (if approved) -->
                                    @if ($user->approval_status === 'approved' && auth()->id() !== $user->id)
                                        <button type="button" class="btn-action disable" title="Disable"
                                            wire:click="disableUser({{ $user->id }})"
                                            wire:confirm="Disable {{ $user->name }}'s account?">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif

                                    <!-- Reset Password -->
                                    <button type="button" class="btn-action reset" title="Reset Password"
                                        wire:click="resetPasswordFor({{ $user->id }})"
                                        wire:confirm="Reset password for {{ $user->name }}?">
                                        <i class="fas fa-key"></i>
                                    </button>

                                    <!-- Delete -->
                                    @if (auth()->id() !== $user->id)
                                        <button type="button" class="btn-action delete" title="Delete"
                                            wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="Delete {{ $user->name }}? This cannot be undone.">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- PAGINATION -->
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No users found</p>
            </div>
        @endif
    </div>
</div>

<style>
    /* ---- User Management List — theme-aware styles ---- */
    .users-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-inner);
    }
    .page-header h1 {
        margin: 0 0 4px 0;
        font-size: 28px;
        font-weight: 700;
        color: var(--text-heading);
    }
    .page-header p {
        margin: 0;
        font-size: 13px;
        color: var(--text-body);
    }

    /* Primary Button */
    .btn {
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-primary {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: white;
    }
    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        color: white;
    }

    /* Alerts */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
    }
    .alert i { font-size: 16px; flex-shrink: 0; }
    .alert-success {
        background: rgba(39,174,96,0.1);
        border: 1px solid rgba(39,174,96,0.2);
        color: #27ae60;
    }
    .alert-error {
        background: rgba(231,76,60,0.1);
        border: 1px solid rgba(231,76,60,0.2);
        color: #e74c3c;
    }
    /* Password result box inside alert */
    .alert-success div[style*="background:white"] {
        background: var(--bg-input) !important;
        color: var(--text-heading);
        border-radius: 6px;
        padding: 10px 14px;
        font-family: monospace;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 16px 20px;
        display: flex;
        gap: 16px;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        transition: all 0.2s;
        font-family: inherit;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }
    .stat-card.active {
        box-shadow: 0 0 0 2px #38bdf8;
    }
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        flex-shrink: 0;
    }
    .stat-icon.total    { background: linear-gradient(135deg, #38bdf8, #2563eb); }
    .stat-icon.pending  { background: linear-gradient(135deg, #f39c12, #e67e22); }
    .stat-icon.approved { background: linear-gradient(135deg, #27ae60, #229954); }
    .stat-icon.disabled { background: linear-gradient(135deg, #7f8c8d, #636e72); }

    .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: var(--text-heading);
    }
    .stat-label {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* Role Breakdown */
    .role-breakdown {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .role-breakdown h3 {
        margin: 0 0 16px 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-heading);
    }
    .breakdown-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
    }
    .breakdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: var(--text-body);
        font-weight: 500;
    }

    /* Badges */
    .badge {
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 600;
        min-width: 36px;
        text-align: center;
        display: inline-block;
    }
    .badge-blue   { background: rgba(56,189,248,0.12);  color: #38bdf8; }
    .badge-green  { background: rgba(39,174,96,0.12);   color: #27ae60; }
    .badge-orange { background: rgba(243,156,18,0.12);  color: #f39c12; }
    .badge-purple { background: rgba(139,92,246,0.12);  color: #8b5cf6; }
    .badge-red    { background: rgba(231,76,60,0.12);   color: #e74c3c; }
    /* Role badge (getRoleLabel) */
    .badge-info   { background: rgba(56,189,248,0.12);  color: #38bdf8; }
    /* Status badges */
    .badge-success   { background: rgba(39,174,96,0.12);  color: #27ae60; }
    .badge-warning   { background: rgba(243,156,18,0.12); color: #f39c12; }
    .badge-danger    { background: rgba(231,76,60,0.12);  color: #e74c3c; }
    .badge-secondary { background: rgba(127,140,141,0.12); color: #7f8c8d; }

    /* Filters */
    .filters-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 16px;
        display: flex;
        gap: 12px;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .filters-form {
        display: flex;
        gap: 12px;
        flex: 1;
    }
    .filter-select {
        padding: 8px 12px;
        border: 1px solid var(--border-input);
        border-radius: 6px;
        font-size: 13px;
        background: var(--bg-input);
        color: var(--text-heading);
        cursor: pointer;
        font-family: inherit;
        transition: border-color 0.2s;
    }
    .filter-select:focus {
        outline: none;
        border-color: #38bdf8;
    }

    /* Users Table Card */
    .users-table-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        transition: opacity 0.15s;
    }
    .users-table-card.table-loading { opacity: 0.6; }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }
    .users-table thead {
        background: transparent;
    }
    .users-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border-inner);
    }
    .users-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-inner);
        font-size: 13px;
        color: var(--text-body);
        vertical-align: middle;
    }
    .users-table tbody tr:hover {
        background: var(--bg-input);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
    }
    .btn-action {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        border: none;
        background: var(--bg-input);
        color: var(--text-body);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        transition: all 0.15s;
        text-decoration: none;
    }
    .btn-action:hover {
        background: var(--border-inner);
        color: var(--text-heading);
    }
    .btn-action.approve {
        background: rgba(39,174,96,0.12);
        color: #27ae60;
    }
    .btn-action.approve:hover { background: rgba(39,174,96,0.25); }

    .btn-action.reject {
        background: rgba(231,76,60,0.1);
        color: #e74c3c;
    }
    .btn-action.reject:hover { background: rgba(231,76,60,0.22); }

    .btn-action.disable {
        background: rgba(243,156,18,0.1);
        color: #f39c12;
    }
    .btn-action.disable:hover { background: rgba(243,156,18,0.22); }

    .btn-action.reset {
        background: rgba(139,92,246,0.1);
        color: #8b5cf6;
    }
    .btn-action.reset:hover { background: rgba(139,92,246,0.22); }

    .btn-action.delete {
        background: rgba(231,76,60,0.1);
        color: #e74c3c;
    }
    .btn-action.delete:hover { background: rgba(231,76,60,0.22); }

    body[data-theme="dark"] .btn-action.approve {
        background: rgba(39,174,96,0.2);
        color: #4ade80;
    }
    body[data-theme="dark"] .btn-action.approve:hover { background: rgba(39,174,96,0.35); }

    body[data-theme="dark"] .btn-action.reject {
        background: rgba(231,76,60,0.15);
        color: #f87171;
    }
    body[data-theme="dark"] .btn-action.reject:hover { background: rgba(231,76,60,0.28); }

    body[data-theme="dark"] .btn-action.disable {
        background: rgba(243,156,18,0.15);
        color: #fbbf24;
    }
    body[data-theme="dark"] .btn-action.disable:hover { background: rgba(243,156,18,0.28); }

    body[data-theme="dark"] .btn-action.reset {
        background: rgba(139,92,246,0.15);
        color: #a78bfa;
    }
    body[data-theme="dark"] .btn-action.reset:hover { background: rgba(139,92,246,0.28); }

    body[data-theme="dark"] .btn-action.delete {
        background: rgba(231,76,60,0.15);
        color: #f87171;
    }
    body[data-theme="dark"] .btn-action.delete:hover { background: rgba(231,76,60,0.28); }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    .empty-state i {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.3;
        display: block;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--border-inner);
    }

    @media (max-width: 768px) {
        .page-header { flex-direction: column; gap: 12px; align-items: flex-start; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .filters-form { flex-direction: column; }
        .users-table { font-size: 12px; }
        .users-table th, .users-table td { padding: 10px 8px; }
    }
</style>