<?php if (isset($component)) { $__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::app-with-sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-with-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

     <?php $__env->slot('header', null, []); ?> Create Users from Patients <?php $__env->endSlot(); ?>

    <div class="bulk-create-page">
        <div class="bulk-card">
            <h1 class="bulk-title">Create Users from Patient List</h1>
            <p class="bulk-subtitle">Automatically create accounts for patients without users</p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li><?php echo e($error); ?></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($patients->isEmpty()): ?>
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <p>All patients already have user accounts!</p>
                    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-back">
                        Back to Users
                    </a>
                </div>
            <?php else: ?>
                <form action="<?php echo e(route('users.bulk-create')); ?>" method="POST" class="bulk-form">
                    <?php echo csrf_field(); ?>

                    <div class="patient-list">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <label class="patient-item">
                                <input type="checkbox" name="patient_ids[]" value="<?php echo e($patient->id); ?>" checked>
                                <div class="patient-info">
                                    <div class="patient-name"><?php echo e($patient->name); ?></div>
                                    <div class="patient-meta">
                                        <?php echo e($patient->email); ?> • <?php echo e($patient->category); ?> • <?php echo e($patient->year_section); ?>

                                    </div>
                                </div>
                            </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-create">
                            <i class="fas fa-users"></i> Create <?php echo e($patients->count()); ?> Users
                        </button>
                    </div>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <style>
        .bulk-create-page {
            display: flex;
            justify-content: center;
            padding: 24px;
        }

        .bulk-card {
            background: white;
            border-radius: 10px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            max-width: 700px;
            width: 100%;
        }

        .bulk-title {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 700;
            color: #2d3e50;
        }

        .bulk-subtitle {
            margin: 0 0 24px 0;
            font-size: 13px;
            color: #95a5a6;
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            background: #fadbd8;
            border: 1px solid #f5b7b1;
            color: #c0392b;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert ul li {
            font-size: 13px;
            margin-bottom: 6px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #95a5a6;
        }

        .empty-state i {
            font-size: 48px;
            color: #27ae60;
            margin-bottom: 16px;
        }

        .empty-state p {
            margin: 0 0 20px 0;
            font-size: 14px;
        }

        .btn-back {
            background: #3498db;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .patient-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 500px;
            overflow-y: auto;
            margin-bottom: 24px;
            border: 1px solid #e8ecf1;
            border-radius: 8px;
            padding: 12px;
        }

        .patient-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .patient-item:hover {
            background: #f9fafb;
        }

        .patient-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .patient-info {
            flex: 1;
        }

        .patient-name {
            font-weight: 700;
            color: #2d3e50;
            font-size: 13px;
        }

        .patient-meta {
            font-size: 11px;
            color: #95a5a6;
            margin-top: 4px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding-top: 16px;
            border-top: 1px solid #e8ecf1;
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
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cancel {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-cancel:hover {
            background: #d4d9e0;
        }

        .btn-create {
            background: #27ae60;
            color: white;
        }

        .btn-create:hover {
            background: #229954;
        }

        @media (max-width: 768px) {
            .bulk-card {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c)): ?>
<?php $attributes = $__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c; ?>
<?php unset($__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c)): ?>
<?php $component = $__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c; ?>
<?php unset($__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\users\create-from-patients.blade.php ENDPATH**/ ?>