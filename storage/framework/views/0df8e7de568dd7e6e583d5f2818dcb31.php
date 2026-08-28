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

     <?php $__env->slot('header', null, []); ?> Clinic Visit Details <?php $__env->endSlot(); ?>

    <div class="clinic-visit-show-page">
        <!-- Header -->
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">Visit Details</h1>
                <p class="page-subtitle"><?php echo e($visit->visit_date->format('F d, Y')); ?></p>
            </div>
            <div class="header-actions">
                <a href="<?php echo e(route('clinic-visit.edit', $visit->id)); ?>" class="btn-edit-main">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?php echo e(route('clinic-visit.index')); ?>" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Patient Info Card -->
        <div class="info-card patient-card">
            <h2 class="card-title">Patient Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Full Name</label>
                    <span class="info-value"><?php echo e($visit->patient->name); ?></span>
                </div>
                <div class="info-item">
                    <label>Category</label>
                    <span class="info-value">
                        <span class="badge <?php echo e($visit->patient->getCategoryBadgeClass()); ?>">
                            <?php echo e($visit->patient->getCategoryLabel()); ?>

                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <label>Year & Section</label>
                    <span class="info-value"><?php echo e($visit->patient->year_section ?? 'N/A'); ?></span>
                </div>
                <div class="info-item">
                    <label>Age</label>
                    <span class="info-value"><?php echo e($visit->patient->age ?? 'N/A'); ?></span>
                </div>
                <div class="info-item">
                    <label>Phone</label>
                    <span class="info-value"><?php echo e($visit->patient->phone ?? 'N/A'); ?></span>
                </div>
                <div class="info-item">
                    <label>Program</label>
                    <span class="info-value"><?php echo e($visit->patient->program ?: 'N/A'); ?></span>
                </div>
            </div>
        </div>

        <!-- Vital Signs Card -->
        <div class="info-card vitals-card">
            <h2 class="card-title">Vital Signs</h2>
            <div class="vitals-grid">
                <div class="vital-item">
                    <span class="vital-label">Temperature</span>
                    <span class="vital-value"><?php echo e($visit->temperature ? $visit->temperature . '°C' : '-'); ?></span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Pulse Rate</span>
                    <span class="vital-value"><?php echo e($visit->pulse_rate ? $visit->pulse_rate . ' bpm' : '-'); ?></span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Respiratory Rate</span>
                    <span class="vital-value"><?php echo e($visit->respiratory_rate ? $visit->respiratory_rate . ' breaths/min' : '-'); ?></span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Blood Pressure</span>
                    <span class="vital-value"><?php echo e($visit->bp_systolic && $visit->bp_diastolic ? $visit->bp_systolic . '/' . $visit->bp_diastolic . ' mmHg' : '-'); ?></span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Height</span>
                    <span class="vital-value"><?php echo e($visit->height ? $visit->height . ' cm' : '-'); ?></span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Weight</span>
                    <span class="vital-value"><?php echo e($visit->weight ? $visit->weight . ' kg' : '-'); ?></span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">BMI</span>
                    <span class="vital-value"><?php echo e($visit->getBMI() ? $visit->getBMI() : '-'); ?></span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">SpO2</span>
                    <span class="vital-value"><?php echo e($visit->spo2 ? $visit->spo2 . '%' : '-'); ?></span>
                </div>
            </div>
        </div>

        <!-- Clinical Info Card -->
        <div class="info-card clinical-card">
            <h2 class="card-title">Clinical Information</h2>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visit->complaints): ?>
                <div class="clinical-section">
                    <h3 class="section-label">Chief Complaints</h3>
                    <p class="section-content"><?php echo e($visit->complaints); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visit->diagnosis): ?>
                <div class="clinical-section">
                    <h3 class="section-label">Diagnosis</h3>
                    <p class="section-content"><?php echo e($visit->diagnosis); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visit->management): ?>
                <div class="clinical-section">
                    <h3 class="section-label">Management/Treatment</h3>
                    <p class="section-content"><?php echo e($visit->management); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visit->notes): ?>
                <div class="clinical-section">
                    <h3 class="section-label">Additional Notes</h3>
                    <p class="section-content"><?php echo e($visit->notes); ?></p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="page-actions">
            <a href="<?php echo e(route('clinic-visit.edit', $visit->id)); ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Record
            </a>
            <a href="<?php echo e(route('clinic-visit.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <style>
        .clinic-visit-show-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
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
            color: #2d3e50;
        }

        .page-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: #95a5a6;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn-edit-main,
        .btn-back {
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-edit-main {
            background: #3498db;
            color: white;
        }

        .btn-edit-main:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-back {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-back:hover {
            background: #d4d9e0;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .card-title {
            margin: 0 0 20px 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3e50;
            border-bottom: 2px solid #e8ecf1;
            padding-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-item label {
            font-size: 11px;
            font-weight: 700;
            color: #95a5a6;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 13px;
            color: #2d3e50;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            width: fit-content;
        }

        .badge-student {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-faculty {
            background: #ede9fe;
            color: #6d28d9;
        }

        .badge-staff {
            background: #dcfce7;
            color: #15803d;
        }

        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
        }

        .vital-item {
            background: #f9fafb;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .vital-label {
            font-size: 11px;
            font-weight: 700;
            color: #95a5a6;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 8px;
        }

        .vital-value {
            font-size: 16px;
            font-weight: 700;
            color: #3498db;
        }

        .clinical-section {
            margin-bottom: 20px;
        }

        .clinical-section:last-child {
            margin-bottom: 0;
        }

        .section-label {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 700;
            color: #2d3e50;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .section-content {
            margin: 0;
            font-size: 13px;
            color: #2d3e50;
            line-height: 1.6;
            padding: 12px;
            background: #f9fafb;
            border-radius: 6px;
            border-left: 4px solid #3498db;
        }

        .page-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
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
            text-decoration: none;
        }

        .btn-primary {
            background: #27ae60;
            color: white;
        }

        .btn-primary:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-secondary:hover {
            background: #d4d9e0;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
            }

            .header-actions {
                width: 100%;
            }

            .btn-edit-main,
            .btn-back {
                flex: 1;
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .vitals-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page-actions {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }

            .info-grid,
            .vitals-grid {
                grid-template-columns: 1fr;
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
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\clinic-visit\show.blade.php ENDPATH**/ ?>