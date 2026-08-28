<div class="appointment-manager">
    <!-- Header -->
    <div class="manager-header">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📅 Appointments</h1>
            <p class="text-gray-600 mt-1">Manage patient appointments and follow-ups</p>
        </div>
        <button wire:click="openForm" class="btn-primary">
            ➕ Schedule Appointment
        </button>
    </div>

    <!-- Stats -->
    <div class="stats-grid mt-6">
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($scheduledCount); ?></div>
                <div class="stat-label">Scheduled</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✓</div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($completedCount); ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✕</div>
            <div class="stat-content">
                <div class="stat-number"><?php echo e($missedCount); ?></div>
                <div class="stat-label">Missed</div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card mt-6">
        <div class="card-filters">
            <input type="text"
                   wire:model.live="searchTerm"
                   placeholder="Search patient name..."
                   class="filter-input">
            
            <select wire:model.live="filterStatus" class="filter-select">
                <option value="">All Status</option>
                <option value="scheduled">Scheduled</option>
                <option value="completed">Completed</option>
                <option value="missed">Missed</option>
            </select>

            <input type="date"
                   wire:model.live="filterDate"
                   class="filter-input">
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="card mt-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($appointments->count() > 0): ?>
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Appointment Date & Time</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr class="table-row">
                                <td class="font-semibold"><?php echo e($appointment->patient->name); ?></td>
                                <td class="text-sm">
                                    <?php echo e($appointment->appointment_date->format('M d, Y - H:i')); ?>

                                </td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($appointment->status === 'scheduled'): ?>
                                        <span class="badge badge-blue">📅 Scheduled</span>
                                    <?php elseif($appointment->status === 'completed'): ?>
                                        <span class="badge badge-green">✓ Completed</span>
                                    <?php else: ?>
                                        <span class="badge badge-red">✕ Missed</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="text-sm text-gray-600">
                                    <?php echo e($appointment->notes ? Str::limit($appointment->notes, 40) : '—'); ?>

                                </td>
                                <td class="action-buttons">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($appointment->status === 'scheduled'): ?>
                                        <button wire:click="markCompleted(<?php echo e($appointment->id); ?>)"
                                                class="btn btn-success"
                                                title="Mark as completed">
                                            ✓
                                        </button>
                                        <button wire:click="markMissed(<?php echo e($appointment->id); ?>)"
                                                class="btn btn-warning"
                                                title="Mark as missed">
                                            ✕
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <button wire:click="editAppointment(<?php echo e($appointment->id); ?>)"
                                            class="btn btn-edit">
                                        ✏️
                                    </button>
                                    <button wire:click="deleteAppointment(<?php echo e($appointment->id); ?>)"
                                            class="btn btn-delete"
                                            onclick="confirm('Delete this appointment?') || event.preventDefault()">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <?php echo e($appointments->links()); ?>

            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>📭 No appointments found.</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Schedule Appointment Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
        <div class="modal-overlay" wire:click="closeForm">
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h2><?php echo e($appointmentId ? '✏️ Edit Appointment' : '➕ Schedule New Appointment'); ?></h2>
                    <button wire:click="closeForm" class="modal-close">✕</button>
                </div>

                <form wire:submit="saveAppointment">
                    <div class="form-group">
                        <label>Patient *</label>
                        <select wire:model="patient_id"
                                class="form-input <?php echo e($errors->has('patient_id') ? 'error' : ''); ?>">
                            <option value="">Select a patient</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $approvedPatients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($patient->id); ?>">
                                    <?php echo e($patient->name); ?> (<?php echo e($patient->email); ?>)
                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Appointment Date & Time *</label>
                        <input type="datetime-local"
                               wire:model="appointment_date"
                               class="form-input <?php echo e($errors->has('appointment_date') ? 'error' : ''); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['appointment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Status *</label>
                        <select wire:model="status"
                                class="form-input <?php echo e($errors->has('status') ? 'error' : ''); ?>">
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="missed">Missed</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea wire:model="notes"
                                  placeholder="Add any notes about the appointment (optional)"
                                  rows="4"
                                  class="form-input"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="button" wire:click="closeForm" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">
                            <?php echo e($appointmentId ? '💾 Update' : '➕ Schedule'); ?> Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <style>
        .appointment-manager { padding: 0; }

        .manager-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .text-3xl { font-size: 28px; font-weight: 700; color: var(--text-heading); margin: 0; }
        .text-gray-600 { font-size: 13px; color: var(--text-body); margin: 4px 0 0 0; }
        .mt-1 { margin-top: 4px; }
        .mt-6 { margin-top: 20px; }
        .font-semibold { font-weight: 600; color: var(--text-heading); }
        .text-sm { font-size: 12px; color: var(--text-body); }
        .text-gray-600 { color: var(--text-body); }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        }

        .stat-card:hover { transform: translateY(-2px); }

        .stat-icon {
            font-size: 22px;
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-heading);
            line-height: 1;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 4px;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .card-filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-input, .filter-select {
            border: 1px solid var(--border-input);
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
            background: var(--bg-input);
            color: var(--text-heading);
        }

        .filter-input:focus, .filter-select:focus {
            border-color: #38bdf8;
            outline: none;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
        }

        .filter-input { flex: 1; min-width: 150px; }

        .table-responsive { overflow-x: auto; }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dashboard-table thead th {
            background: transparent;
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 2px solid var(--border-inner);
        }

        .table-row { border-bottom: 1px solid var(--border-inner); }
        .table-row:hover { background: var(--bg-input); }
        .table-row td { padding: 12px 16px; font-size: 13px; color: var(--text-body); }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-blue  { background: rgba(56, 189, 248, 0.1);  color: #38bdf8; }
        .badge-green { background: rgba(39, 174, 96, 0.1);   color: #27ae60; }
        .badge-red   { background: rgba(231, 76, 60, 0.1);   color: #e74c3c; }

        .action-buttons { display: flex; gap: 6px; }

        .btn {
            padding: 6px 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.15s;
        }

        .btn-success { background: rgba(39,174,96,0.12); color: #27ae60; }
        .btn-success:hover { background: rgba(39,174,96,0.2); }
        .btn-warning { background: rgba(243,156,18,0.12); color: #f39c12; }
        .btn-warning:hover { background: rgba(243,156,18,0.2); }
        .btn-edit { background: rgba(56,189,248,0.12); color: #38bdf8; }
        .btn-edit:hover { background: rgba(56,189,248,0.2); }
        .btn-delete { background: rgba(231,76,60,0.12); color: #e74c3c; }
        .btn-delete:hover { background: rgba(231,76,60,0.2); }

        .btn-primary {
            padding: 10px 20px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-primary:hover { opacity: 0.9; transform: translateY(-2px); }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 10px;
            padding: 0;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-inner);
        }

        .modal-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .modal-close {
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            border-radius: 6px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.15s;
        }

        .modal-close:hover { border-color: #e74c3c; color: #e74c3c; }

        .form-group {
            padding: 16px 24px 0;
            margin-bottom: 4px;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            border: 1px solid var(--border-input);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 13px;
            background: var(--bg-input);
            color: var(--text-heading);
            transition: border-color 0.2s;
        }

        .form-input:focus { border-color: #38bdf8; outline: none; box-shadow: 0 0 0 3px rgba(56,189,248,0.1); }
        .form-input.error { border-color: #e74c3c; }
        .error-text { font-size: 11px; color: #e74c3c; margin-top: 4px; display: block; }

        .form-actions {
            padding: 16px 24px;
            border-top: 1px solid var(--border-inner);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-secondary {
            padding: 10px 20px;
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            color: var(--text-body);
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-secondary:hover { border-color: #38bdf8; color: #38bdf8; }

        @media (max-width: 768px) {
            .manager-header { flex-direction: column; gap: 12px; }
            .card-filters { flex-direction: column; }
            .filter-input { min-width: 100%; }
        }
    </style>
</div><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\livewire\appointments\appointment-manager.blade.php ENDPATH**/ ?>