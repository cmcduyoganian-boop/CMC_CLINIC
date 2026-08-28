<div class="appointment-form-page">
    <div class="form-card">
        <h1 class="form-title">Schedule New Appointment</h1>
        <p class="form-description">Book an appointment for a patient</p>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul style="margin:0;padding-left:20px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <li><?php echo e($error); ?></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSelfService): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Schedule an appointment with the clinic. We'll confirm your booking soon!
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form wire:submit="save">
            <div class="form-section">
                <h2 class="section-title">Patient Information</h2>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSelfService): ?>
                    <div class="form-group">
                        <label class="form-label">Appointment for</label>
                        <p class="form-text-display">
                            <strong><?php echo e($lockedPatientName); ?></strong>
                            <span class="badge"><?php echo e(ucfirst($lockedPatientCategory)); ?></span>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Patient Name *</label>
                            <div class="patient-search-wrapper">
                                <input
                                    type="text"
                                    wire:model.live.debounce.300ms="patientName"
                                    wire:focus="$set('showPatientDropdown', true)"
                                    class="form-control"
                                    placeholder="Type patient name..."
                                    required
                                    autocomplete="off">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showPatientDropdown && $patientName): ?>
                                    <div class="patient-dropdown show" wire:click.outside="hideDropdown">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->matchingPatients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                            <div class="patient-option" wire:click="selectPatient(<?php echo e($match->id); ?>)">
                                                <div class="patient-option-name"><?php echo e($match->name); ?></div>
                                                <div class="patient-option-detail"><?php echo e($match->year_section ?: '-'); ?> &bull; <?php echo e(ucfirst($match->category)); ?></div>
                                            </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            <div class="patient-option">
                                                <div class="patient-option-name">No patients found</div>
                                                <div class="patient-option-detail">A new patient record will be created</div>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <small class="form-hint">Type to search existing patients or enter new name</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Category *</label>
                            <select wire:model="patientCategory" class="form-control" required>
                                <option value="">-- Select Category --</option>
                                <option value="student">Student</option>
                                <option value="faculty">Faculty</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Year &amp; Section</label>
                            <input type="text" wire:model="patientYearSection" class="form-control" placeholder="e.g., BSCS-2A">
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                    <span wire:loading.remove wire:target="save"><i class="fas fa-calendar-plus"></i> Schedule Appointment</span>
                    <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i> Scheduling...</span>
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

    .alert-info {
        background: rgba(56,189,248,0.08);
        border: 1px solid rgba(56,189,248,0.2);
        color: #38bdf8;
        display: flex;
        align-items: center;
        gap: 8px;
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

    .form-row-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
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

    .form-hint {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
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

    .patient-search-wrapper {
        position: relative;
    }

    .patient-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 10;
        display: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .patient-dropdown.show {
        display: block;
    }

    .patient-option {
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid var(--border-inner);
        font-size: 13px;
        transition: background 0.15s;
    }

    .patient-option:hover {
        background: var(--bg-input);
    }

    .patient-option-name {
        font-weight: 600;
        color: var(--text-heading);
    }

    .patient-option-detail {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 2px;
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

        .form-row-3 {
            grid-template-columns: repeat(2, 1fr);
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
</style><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\livewire\appointments\appointment-create-form.blade.php ENDPATH**/ ?>