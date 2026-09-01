<div class="appointment-form-page">
    <div class="form-card">
        <h1 class="form-title">Edit Appointment</h1>
        <p class="form-description">Update the appointment details</p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul style="margin:0;padding-left:20px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li><?php echo e($error); ?></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form wire:submit="save">
            <div class="form-section">
                <h2 class="section-title">Patient Information</h2>

                <div class="form-group">
                    <label class="form-label">Patient</label>
                    <p class="form-text-display">
                        <strong><?php echo e($appointment->patient->name); ?></strong>
                        <span class="badge"><?php echo e(ucfirst($appointment->patient->category)); ?></span>
                    </p>
                </div>
            </div>

            <div class="form-section">
                <h2 class="section-title">Appointment Details</h2>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" wire:model="appointmentDate" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Time *</label>
                        <input type="time" wire:model="appointmentTime" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select wire:model="status" class="form-control" required>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="no-show">No-show</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Reason for Appointment</label>
                    <textarea wire:model="reason" class="form-control" rows="2" placeholder="e.g., Follow-up checkup, Consultation..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Additional Notes</label>
                    <textarea wire:model="notes" class="form-control" rows="2" placeholder="Any additional notes..."></textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-cancel">Cancel</a>
                <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save"><i class="fas fa-save"></i> Update Appointment</span>
                    <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i> Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .appointment-form-page {
        display: flex;
        justify-content: center;
        padding: 24px;
    }

    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        max-width: 600px;
        width: 100%;
    }

    .form-title {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .form-description {
        margin: 0 0 24px 0;
        font-size: 13px;
        color: var(--text-body);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 13px;
    }

    .alert-danger {
        background: rgba(231,76,60,0.1);
        border: 1px solid rgba(231,76,60,0.2);
        color: #e74c3c;
    }

    .alert ul li {
        font-size: 13px;
        margin-bottom: 6px;
    }

    .form-section {
        margin-bottom: 24px;
    }

    .section-title {
        margin: 0 0 16px 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-heading);
        border-bottom: 2px solid var(--border-inner);
        padding-bottom: 8px;
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        display: block;
    }

    .form-text-display {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border: 1px solid var(--border-input);
        border-radius: 6px;
        background: var(--bg-input);
        margin: 0;
        font-size: 13px;
        color: var(--text-heading);
    }

    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        background: rgba(56,189,248,0.1);
        color: #38bdf8;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .form-control {
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        background: var(--bg-input);
        color: var(--text-heading);
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: #38bdf8;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--border-inner);
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        font-family: 'Figtree', sans-serif;
        text-decoration: none;
    }

    .btn-cancel {
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        color: var(--text-body);
    }

    .btn-cancel:hover {
        opacity: 0.8;
    }

    .btn-save {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: white;
        border: none;
    }

    .btn-save:hover:not(:disabled) {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .btn-save:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 20px;
        }

        .form-row-2 {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style><?php /**PATH C:\laragon\www\cmc_clinic\resources\views/livewire/appointments/appointment-edit-form.blade.php ENDPATH**/ ?>