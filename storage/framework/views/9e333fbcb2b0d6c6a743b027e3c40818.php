<div class="clinic-visit-form-page">
    <!-- Form Header -->
    <div class="form-header">
        <h1 class="form-title">Add New Patient</h1>
        <p class="form-description">Register a new patient record</p>
    </div>

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
        <!-- Basic Information -->
        <div class="form-section">
            <h2 class="section-title">Basic Information</h2>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" wire:model="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select wire:model="category" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">Program</label>
                    <select wire:model="program" class="form-control">
                        <option value="">-- Select Program --</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($p); ?>"><?php echo e($p); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Year &amp; Section</label>
                    <input type="text" wire:model="yearSection" class="form-control" placeholder="e.g., 2A">
                </div>

                <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="number" wire:model="age" class="form-control">
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="form-section">
            <h2 class="section-title">Contact Information</h2>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" wire:model="email" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" wire:model="phone" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea wire:model="address" class="form-control" rows="2"></textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions">
            <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save"><i class="fas fa-user-plus"></i> Add Patient</span>
                <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
            </button>
        </div>
    </form>
</div>

<style>
    .clinic-visit-form-page {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-header {
        margin-bottom: 4px;
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
        margin-bottom: 4px;
        font-size: 13px;
    }

    .alert-danger {
        background: rgba(231, 76, 60, 0.1);
        border: 1px solid rgba(231, 76, 60, 0.2);
        color: #e74c3c;
    }

    .alert ul li {
        font-size: 13px;
        margin-bottom: 6px;
    }

    .clinic-visit-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
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
        margin-bottom: 16px;
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

    .form-control {
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        background: var(--bg-input);
        color: var(--text-heading);
        width: 100%;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #38bdf8;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 8px;
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
    }
</style><?php /**PATH C:\laragon\www\cmc_clinic\resources\views/livewire/patients/patient-create-form.blade.php ENDPATH**/ ?>