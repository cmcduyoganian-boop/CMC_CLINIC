<div class="user-management-container">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashMessage): ?>
        <div class="alert alert-<?php echo e($flashType); ?> alert-dismissible fade show" role="alert">
            <?php echo e($flashMessage); ?>

            <button type="button" class="btn-close" wire:click="dismissFlash"></button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">User Management</h2>
            <p class="text-muted">Manage clinic system users and approvals</p>
        </div>
        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create User
        </a>
    </div>

    <!-- Statistics Cards (REAL-TIME UPDATES) -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Users</p>
                            <h3 class="mb-0" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'total-users'; ?>wire:key="total-users"><?php echo e($totalUsers); ?></h3>
                        </div>
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Pending Approval</p>
                            <h3 class="mb-0 text-warning" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'pending-count'; ?>wire:key="pending-count"><?php echo e($pendingCount); ?></h3>
                        </div>
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Approved</p>
                            <h3 class="mb-0 text-success" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'approved-count'; ?>wire:key="approved-count"><?php echo e($approvedCount); ?></h3>
                        </div>
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Disabled</p>
                            <h3 class="mb-0 text-danger" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'disabled-count'; ?>wire:key="disabled-count"><?php echo e($disabledCount); ?></h3>
                        </div>
                        <div class="stats-icon bg-danger">
                            <i class="fas fa-ban"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users by Role -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Users by Role</h5>
            <div class="row">
                <div class="col-md-2 col-sm-4 mb-3">
                    <div class="role-badge">
                        <span class="badge-number" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'students'; ?>wire:key="students"><?php echo e($studentCount); ?></span>
                        <span class="badge-label">Students</span>
                    </div>
                </div>

                <div class="col-md-2 col-sm-4 mb-3">
                    <div class="role-badge">
                        <span class="badge-number" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'faculty'; ?>wire:key="faculty"><?php echo e($facultyCount); ?></span>
                        <span class="badge-label">Faculty</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 mb-3">
                    <div class="role-badge">
                        <span class="badge-number" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'staff'; ?>wire:key="staff"><?php echo e($staffCount); ?></span>
                        <span class="badge-label">Staff</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 mb-3">
                    <div class="role-badge">
                        <span class="badge-number" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'nurses'; ?>wire:key="nurses"><?php echo e($clinicNurseCount); ?></span>
                        <span class="badge-label">Clinic Nurses</span>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 mb-3">
                    <div class="role-badge">
                        <span class="badge-number" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'clinic-staff'; ?>wire:key="clinic-staff"><?php echo e($clinicStaffCount); ?></span>
                        <span class="badge-label">Clinic Staff</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <input 
                        type="text" 
                        class="form-control" 
                        placeholder="Search by name, email, username..."
                        wire:model.live="search"
                    >
                </div>
                <div class="col-lg-3 mb-3">
                    <select class="form-control" wire:model.live="filterRole">
                        <option value="">-- All Roles --</option>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="staff">Staff</option>
                        <option value="clinic_nurse">Clinic Nurse</option>
                        <option value="clinic_staff">Clinic Staff</option>
                    </select>
                </div>
                <div class="col-lg-3 mb-3">
                    <select class="form-control" wire:model.live="filterStatus">
                        <option value="">-- All Status --</option>
                        <option value="pending">Pending Approval</option>
                        <option value="approved">Approved</option>
                        <option value="disabled">Disabled</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-3">
                    <button class="btn btn-secondary w-100" wire:click="$refresh">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingRegistrations->isNotEmpty() && (!$filterStatus || $filterStatus === 'pending')): ?>
        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning-subtle">
                <strong><i class="fas fa-envelope"></i> Registrations Awaiting OTP Verification</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>USERNAME</th>
                            <th>EMAIL</th>
                            <th>ROLE</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingRegistrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pending): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'pending-registration-'.e($pending->id).''; ?>wire:key="pending-registration-<?php echo e($pending->id); ?>">
                                <td><strong><?php echo e($pending->name); ?></strong></td>
                                <td><code><?php echo e($pending->username); ?></code></td>
                                <td><small><?php echo e($pending->email); ?></small></td>
                                <td><?php echo e(ucfirst(str_replace('_', ' ', $pending->role))); ?></td>
                                <td><span class="badge bg-warning text-dark">OTP Pending</span></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm"
                                            wire:click="denyPendingRegistration(<?php echo e($pending->id); ?>)"
                                            wire:confirm="Deny this registration?"
                                            wire:loading.attr="disabled">
                                        <i class="fas fa-times"></i> Deny
                                    </button>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Users Table (REAL-TIME) -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr class="bg-light">
                        <th>NAME</th>
                        <th>USERNAME</th>
                        <th>EMAIL</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>ACTIVE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'user-'.e($user->id).''; ?>wire:key="user-<?php echo e($user->id); ?>">
                            <td>
                                <strong><?php echo e($user->name); ?></strong>
                            </td>
                            <td>
                                <code><?php echo e($user->username); ?></code>
                            </td>
                            <td>
                                <small><?php echo e($user->email); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo e($user->getRoleLabel()); ?></span>
                            </td>
                            <td>
                                <span class="badge <?php echo e($user->getStatusBadgeClass()); ?>">
                                    <?php echo e($user->getStatusLabel()); ?>

                                </span>
                            </td>

                            <td>
                                <span class="badge <?php echo e($user->is_active ? 'bg-success' : 'bg-danger'); ?>">
                                    <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- View Button -->
                                    <a href="<?php echo e(route('users.show', $user->id)); ?>" class="btn btn-outline-secondary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Approve Button (if pending) -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isPending()): ?>
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-sm"
                                            wire:click="approveUser(<?php echo e($user->id); ?>)"
                                            wire:confirm="Are you sure you want to approve this account?"
                                            title="Confirm"
                                            wire:loading.attr="disabled"
                                        >
                                            <i class="fas fa-check"></i> Confirm
                                        </button>

                                        <!-- Reject Button -->
                                        <button 
                                            type="button" 
                                            class="btn btn-danger btn-sm"
                                            wire:click="rejectUser(<?php echo e($user->id); ?>)"
                                            wire:confirm="Are you sure you want to reject this account? This action cannot be undone immediately."
                                            title="Deny"
                                            wire:loading.attr="disabled"
                                        >
                                            <i class="fas fa-times"></i> Deny
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <!-- Disable Button (if approved) -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isApproved() && $user->id !== 1): ?>
                                        <button 
                                            type="button" 
                                            class="btn btn-warning btn-sm"
                                            wire:click="disableUser(<?php echo e($user->id); ?>)"
                                            wire:confirm="Are you sure you want to disable this account?"
                                            title="Disable"
                                            wire:loading.attr="disabled"
                                        >
                                            <i class="fas fa-ban"></i> Disable
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <!-- Reactivate Button (if disabled) -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->isDisabled() && $user->id !== 1): ?>
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-sm"
                                            wire:click="reactivateUser(<?php echo e($user->id); ?>)"
                                            wire:confirm="Are you sure you want to reactivate this account?"
                                            title="Reactivate"
                                            wire:loading.attr="disabled"
                                        >
                                            <i class="fas fa-check"></i> Reactivate
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <!-- Delete Button -->
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-danger btn-sm"
                                        title="Delete"
                                        onclick="alert('Delete functionality coming soon')"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>

                                                                        
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No users found
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            <?php echo e($users->links()); ?>

        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading.delay class="position-fixed bottom-0 end-0 m-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <style>
        /* ---- User Management Admin — theme-aware styles ---- */
        .user-management-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Page Header */
        .user-management-container .d-flex.justify-content-between h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
            margin: 0 0 4px 0;
        }
        .user-management-container .d-flex.justify-content-between .text-muted {
            font-size: 13px;
            color: var(--text-body);
            margin: 0;
        }

        /* Primary action button */
        .user-management-container .btn-primary {
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: white;
            border-radius: 8px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .user-management-container .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            color: white;
        }

        /* Cards */
        .user-management-container .card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .user-management-container .card-body {
            padding: 20px;
        }
        .user-management-container .card-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border-inner);
            background: transparent;
        }
        .user-management-container .card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-heading);
            margin-bottom: 16px;
        }

        /* Stats cards */
        .stats-card {
            border-left: 4px solid #38bdf8;
            transition: all 0.2s;
        }
        .stats-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            transform: translateY(-2px);
        }
        .stats-card h3 {
            color: var(--text-heading);
        }
        .stats-card .text-muted {
            font-size: 12px;
            color: var(--text-muted) !important;
        }
        .stats-card .text-warning { color: #f39c12 !important; }
        .stats-card .text-success { color: #27ae60 !important; }
        .stats-card .text-danger  { color: #e74c3c !important; }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            flex-shrink: 0;
        }
        .stats-icon.bg-primary  { background: linear-gradient(135deg, #38bdf8, #2563eb); }
        .stats-icon.bg-warning  { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .stats-icon.bg-success  { background: linear-gradient(135deg, #27ae60, #229954); }
        .stats-icon.bg-danger   { background: linear-gradient(135deg, #e74c3c, #c0392b); }

        /* Role badge mini-cards */
        .role-badge {
            background: var(--bg-input);
            border: 1px solid var(--border-inner);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .badge-number {
            font-size: 24px;
            font-weight: 700;
            color: #38bdf8;
        }
        .badge-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        /* Filter inputs */
        .user-management-container .form-control {
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            border-radius: 6px;
            color: var(--text-heading);
            padding: 8px 12px;
            font-size: 13px;
            width: 100%;
        }
        .user-management-container .form-control:focus {
            border-color: #38bdf8;
            outline: none;
            box-shadow: none;
        }
        .user-management-container .btn-secondary {
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            color: var(--text-body);
            border-radius: 6px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .user-management-container .btn-secondary:hover {
            border-color: #38bdf8;
            color: var(--text-heading);
        }

        /* Table */
        .user-management-container .table-responsive {
            overflow-x: auto;
        }
        .user-management-container .table {
            width: 100%;
            border-collapse: collapse;
        }
        .user-management-container .table thead tr.bg-light {
            background: transparent !important;
        }
        .user-management-container .table thead th {
            background: transparent;
            border-bottom: 2px solid var(--border-inner);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
        }
        .user-management-container .table tbody td {
            padding: 12px 16px;
            color: var(--text-body);
            border-bottom: 1px solid var(--border-inner);
            font-size: 13px;
            vertical-align: middle;
        }
        .user-management-container .table tbody tr:hover {
            background: var(--bg-input);
        }
        .user-management-container .table tbody td strong {
            color: var(--text-heading);
            font-weight: 600;
        }
        .user-management-container .table tbody td code {
            background: var(--bg-input);
            color: #38bdf8;
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 12px;
        }
        .user-management-container .table .text-center.text-muted {
            color: var(--text-muted);
        }

        /* Badges */
        .user-management-container .badge {
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
        }
        .user-management-container .badge.bg-info,
        .user-management-container .badge.badge-info {
            background: rgba(56,189,248,0.1) !important;
            color: #38bdf8;
        }
        /* Status badges (rendered by getStatusBadgeClass()) */
        .user-management-container .badge.bg-warning,
        .user-management-container .badge.badge-warning {
            background: rgba(243,156,18,0.1) !important;
            color: #f39c12;
        }
        .user-management-container .badge.bg-success,
        .user-management-container .badge.badge-success {
            background: rgba(39,174,96,0.1) !important;
            color: #27ae60;
        }
        .user-management-container .badge.bg-danger,
        .user-management-container .badge.badge-danger {
            background: rgba(231,76,60,0.1) !important;
            color: #e74c3c;
        }
        .user-management-container .badge.bg-secondary,
        .user-management-container .badge.badge-secondary {
            background: rgba(127,140,141,0.1) !important;
            color: #7f8c8d;
        }

        /* Action buttons */
        .user-management-container .btn-group .btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s;
            text-decoration: none;
        }
        .user-management-container .btn-outline-secondary {
            background: transparent;
            border-color: var(--border-input);
            color: var(--text-body);
        }
        .user-management-container .btn-outline-secondary:hover {
            background: var(--bg-input);
            color: var(--text-heading);
        }
        .user-management-container .btn-success.btn-sm {
            background: rgba(39,174,96,0.12);
            border-color: rgba(39,174,96,0.4);
            color: #27ae60;
        }
        .user-management-container .btn-success.btn-sm:hover {
            background: rgba(39,174,96,0.25);
        }
        .user-management-container .btn-danger.btn-sm,
        .user-management-container .btn-outline-danger.btn-sm {
            background: rgba(231,76,60,0.1);
            border-color: rgba(231,76,60,0.4);
            color: #e74c3c;
        }
        .user-management-container .btn-danger.btn-sm:hover,
        .user-management-container .btn-outline-danger.btn-sm:hover {
            background: rgba(231,76,60,0.22);
        }
        .user-management-container .btn-warning.btn-sm {
            background: rgba(243,156,18,0.1);
            border-color: rgba(243,156,18,0.4);
            color: #f39c12;
        }
        .user-management-container .btn-warning.btn-sm:hover {
            background: rgba(243,156,18,0.22);
        }

        /* Alert */
        .user-management-container .alert {
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-management-container .alert-success {
            background: rgba(39,174,96,0.1);
            border: 1px solid rgba(39,174,96,0.2);
            color: #27ae60;
        }
        .user-management-container .alert-danger {
            background: rgba(231,76,60,0.1);
            border: 1px solid rgba(231,76,60,0.2);
            color: #e74c3c;
        }
        .user-management-container .btn-close {
            background: none;
            border: none;
            cursor: pointer;
            margin-left: auto;
            opacity: 0.6;
            font-size: 14px;
        }
        .user-management-container .btn-close:hover { opacity: 1; }

        [wire:loading] {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
</div>
<?php /**PATH C:\laragon\www\cmc_clinic\resources\views\livewire\admin\user-management.blade.php ENDPATH**/ ?>