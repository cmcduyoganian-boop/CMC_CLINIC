<div class="clinic-visit-form-page">
    <!-- Form Header -->
    <div class="form-header">
        <h1 class="form-title">Record Clinic Visit</h1>
        <p class="form-description">Enter patient information and vital signs</p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:20px;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li><?php echo e($error); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form wire:submit="save" class="clinic-visit-form">
        <!-- Visit Date + Patient Information -->
        <div class="form-section">
            <h2 class="section-title">Visit &amp; Patient Information</h2>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Date *</label>
                    <input type="date" wire:model="visitDate" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Visit Type *</label>
                    <select wire:model="visitType" class="form-control" required>
                        <option value="walk_in">Walk-in</option>
                        <option value="appointment">Appointment</option>
                        <option value="follow_up">Follow-up</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Full Name *</label>
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
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select wire:model.live="patientCategory" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><?php echo e(in_array($patientCategory, ['faculty', 'staff']) ? 'Department' : 'Program'); ?></label>
                    <input type="text" wire:model="patientProgram" class="form-control"
                           placeholder="<?php echo e(in_array($patientCategory, ['faculty', 'staff']) ? 'Enter department' : 'Enter program'); ?>">
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($patientCategory === 'student'): ?>
                    <div class="form-group">
                        <label class="form-label">Year &amp; Section</label>
                        <input type="text" wire:model="patientYearSection" class="form-control" placeholder="e.g., 2A">
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="number" wire:model="patientAge" class="form-control" min="0" max="150" placeholder="Enter age">
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" wire:model="patientPhone" class="form-control" maxlength="30" placeholder="Enter phone number">
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" wire:model="patientAddress" class="form-control" maxlength="500" placeholder="Enter address">
                </div>
            </div>
        </div>

        <!-- Vital Signs -->
        <div class="form-section">
            <h2 class="section-title">Vital Signs</h2>

            <div class="vitals-grid">
                <div class="form-group">
                    <label class="form-label">Temperature - T (°C)</label>
                    <input type="number" wire:model="temperature" class="form-control" step="0.1" placeholder="37.5">
                </div>

                <div class="form-group">
                    <label class="form-label">Pulse Rate - PR (bpm)</label>
                    <input type="number" wire:model="pulseRate" class="form-control" placeholder="72">
                </div>

                <div class="form-group">
                    <label class="form-label">Respiratory Rate - RR (breaths/min)</label>
                    <input type="number" wire:model="respiratoryRate" class="form-control" placeholder="16">
                </div>

                <div class="form-group">
                    <label class="form-label">BP Systolic (mmHg)</label>
                    <input type="number" wire:model="bpSystolic" class="form-control" placeholder="120">
                </div>

                <div class="form-group">
                    <label class="form-label">BP Diastolic (mmHg)</label>
                    <input type="number" wire:model="bpDiastolic" class="form-control" placeholder="80">
                </div>

                <div class="form-group">
                    <label class="form-label">Height - HT (cm)</label>
                    <input type="number" wire:model.live="height" class="form-control" step="0.5" placeholder="170">
                </div>

                <div class="form-group">
                    <label class="form-label">Weight - WT (kg)</label>
                    <input type="number" wire:model.live="weight" class="form-control" step="0.1" placeholder="65">
                </div>

                <div class="form-group">
                    <label class="form-label">BMI (auto-calculated)</label>
                    <div class="bmi-display">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->bmi !== null): ?>
                            <span class="bmi-value"><?php echo e($this->bmi); ?></span>
                            <span class="bmi-badge <?php echo e($this->bmiCategory['class']); ?>"><?php echo e($this->bmiCategory['label']); ?></span>
                        <?php else: ?>
                            <span class="bmi-placeholder">Enter height &amp; weight</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">SpO2 (%)</label>
                    <input type="number" wire:model="spo2" class="form-control" placeholder="98">
                </div>
            </div>
        </div>

        <!-- Clinical Information -->
        <div class="form-section">
            <h2 class="section-title">Clinical Information</h2>

            <div class="form-group">
                <label class="form-label">Chief Complaints</label>
                <textarea wire:model="complaints" class="form-control" rows="3" placeholder="Patient's main complaints..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Management/Treatment</label>
                <textarea wire:model="management" class="form-control" rows="3" placeholder="Treatment plan and medications..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Diagnosis</label>
                <textarea wire:model="diagnosis" class="form-control" rows="3" placeholder="Diagnosed condition..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Additional Notes</label>
                <textarea wire:model="notes" class="form-control" rows="2" placeholder="Any other notes..."></textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions">
            <a href="<?php echo e(route('clinic-visit.index')); ?>" class="btn btn-cancel" onclick="return confirm('Discard changes?')">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save"><i class="fas fa-save"></i> Save Visit</span>
                <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
            </button>
        </div>
    </form>
</div>


<style>
    .clinic-visit-form-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-header {
        margin-bottom: 16px;
    }

    .form-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .form-description {
        margin: 4px 0 0 0;
        font-size: 13px;
        color: var(--text-body);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
    }

    .alert-success {
        background: rgba(39,174,96,0.1);
        border: 1px solid rgba(39,174,96,0.2);
        color: #27ae60;
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

    .clinic-visit-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-section {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .section-title {
        margin: 0 0 20px 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-heading);
        border-bottom: 1px solid var(--border-inner);
        padding-bottom: 12px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
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

    .form-control {
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: var(--text-heading);
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56,189,248,0.1);
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
        border: 1px solid var(--border-input);
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 10;
        display: none;
    }

    .patient-dropdown.show {
        display: block;
    }

    .patient-option {
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid var(--border-inner);
        font-size: 13px;
        transition: all 0.2s;
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

    .vitals-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }

    .bmi-display {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 10px 12px;
        min-height: 38px;
    }

    .bmi-value {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .bmi-placeholder {
        font-size: 12px;
        color: var(--text-muted);
        font-style: italic;
    }

    .bmi-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        text-transform: uppercase;
    }

    .bmi-under {
        background: rgba(56,189,248,0.1);
        color: #38bdf8;
    }

    .bmi-normal {
        background: rgba(39,174,96,0.1);
        color: #27ae60;
    }

    .bmi-over {
        background: rgba(243,156,18,0.1);
        color: #f39c12;
    }

    .bmi-obese {
        background: rgba(231,76,60,0.1);
        color: #e74c3c;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border-inner);
    }

    .btn {
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
        font-family: 'Figtree', sans-serif;
        text-decoration: none;
    }

    .btn-cancel {
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        color: var(--text-body);
    }

    .btn-cancel:hover {
        opacity: 0.85;
    }

    .btn-save {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: white;
    }

    .btn-save:hover:not(:disabled) {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .btn-save:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    @media (max-width: 1024px) {
        .form-row-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .form-row-3,
        .form-row-2 {
            grid-template-columns: 1fr;
        }

        .vitals-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .form-title {
            font-size: 22px;
        }

        .vitals-grid {
            grid-template-columns: 1fr;
        }
    }
</style><?php /**PATH C:\laragon\www\cmc_clinic\resources\views/livewire/clinic-visit/clinic-visit-create-form.blade.php ENDPATH**/ ?>