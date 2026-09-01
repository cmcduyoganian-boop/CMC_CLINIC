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

     <?php $__env->slot('header', null, []); ?> Clinic Visits <?php $__env->endSlot(); ?>

    <div class="clinic-visit-list-page">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">Clinic Visit Records</h1>
                <p class="page-description">View and manage all clinic visit records</p>
            </div>
            <a href="<?php echo e(route('clinic-visit.create')); ?>" class="btn-new-visit">
                <i class="fas fa-plus"></i> New Visit
            </a>
        </div>

        <!-- Clinic Visits Table -->
        <div class="table-container">
            <table class="visits-table">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>FULL NAME</th>
                        <th>YEAR & SECTION</th>
                        <th>AGE</th>
                        <th colspan="8">VITAL SIGNS</th>
                        <th>COMPLAINTS</th>
                        <th>DIAGNOSIS</th>
                        <th>MANAGEMENT</th>
                        <th>ACTIONS</th>
                    </tr>
                    <tr class="vital-signs-header">
                        <th colspan="4"></th>
                        <th>T°</th>
                        <th>PR</th>
                        <th>RR</th>
                        <th>BP</th>
                        <th>HT</th>
                        <th>WT</th>
                        <th>BMI</th>
                        <th>SpO2</th>
                        <th colspan="4"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $visits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="visit-row">
                            <td><?php echo e($visit->visit_date->format('m/d/Y')); ?></td>
                            <td class="patient-name"><?php echo e($visit->patient->name ?? 'N/A'); ?></td>
                            <td class="year-section"><?php echo e($visit->patient->year_section ?? 'N/A'); ?></td>
                            <td class="center"><?php echo e($visit->patient->age ?? 'N/A'); ?></td>
                            <td class="vital-sign"><?php echo e($visit->temperature ? $visit->temperature . '°C' : '-'); ?></td>
                            <td class="vital-sign"><?php echo e($visit->pulse_rate ?: '-'); ?></td>
                            <td class="vital-sign"><?php echo e($visit->respiratory_rate ?: '-'); ?></td>
                            <td class="vital-sign"><?php echo e($visit->bp_systolic && $visit->bp_diastolic ? $visit->bp_systolic . '/' . $visit->bp_diastolic : '-'); ?></td>
                            <td class="vital-sign"><?php echo e($visit->height ? $visit->height . 'cm' : '-'); ?></td>
                            <td class="vital-sign"><?php echo e($visit->weight ? $visit->weight . 'kg' : '-'); ?></td>
                            <td class="vital-sign"><?php echo e($visit->getBMI() ?: '-'); ?></td>
                            <td class="vital-sign"><?php echo e($visit->spo2 ? $visit->spo2 . '%' : '-'); ?></td>
                            <td class="text-small"><?php echo e($visit->complaints ?: '-'); ?></td>
                            <td class="text-small"><?php echo e($visit->diagnosis ?: '-'); ?></td>
                            <td class="text-small"><?php echo e($visit->management ?: '-'); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo e(route('clinic-visit.show', $visit->id)); ?>" class="btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('clinic-visit.edit', $visit->id)); ?>" class="btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?php echo e(route('clinic-visit.destroy', $visit->id)); ?>" style="display:inline;" onsubmit="return confirm('Delete this visit record?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="16" style="text-align:center;padding:40px;color:var(--text-muted);">
                                No clinic visits recorded yet.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visits->hasPages()): ?>
            <div class="pagination-section">
                <p class="pagination-info">
                    Showing <?php echo e($visits->firstItem() ?? 0); ?> to <?php echo e($visits->lastItem() ?? 0); ?> of <?php echo e($visits->total()); ?> visits
                </p>
                <div class="pagination">
                    <?php echo e($visits->links()); ?>

                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <style>
        .clinic-visit-list-page {
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
            color: var(--text-heading);
        }

        .page-description {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .btn-new-visit {
            background: #27ae60;
            color: white;
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

        .btn-new-visit:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .table-container {
            background: var(--bg-card);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .visits-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .visits-table thead tr:first-child th {
            padding: 14px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: 1px solid #2980b9;
        }

        .vital-signs-header th {
            background: #34495e !important;
            padding: 10px 6px !important;
            font-size: 10px !important;
        }

        .visits-table tbody tr {
            border-bottom: 1px solid var(--border-inner);
            transition: all 0.2s;
        }

        .visits-table tbody tr:hover {
            background: var(--bg-input);
        }

        .visits-table td {
            padding: 12px 10px;
            color: var(--text-heading);
            border-right: 1px solid var(--border-inner);
        }

        .visits-table td:last-child {
            border-right: none;
        }

        .patient-name {
            font-weight: 700;
            color: #3498db;
            min-width: 120px;
        }

        .year-section {
            font-weight: 600;
            color: #9b59b6;
            min-width: 100px;
        }

        .center {
            text-align: center;
        }

        .vital-sign {
            text-align: center;
            font-weight: 600;
            color: var(--text-heading);
            min-width: 45px;
        }

        .text-small {
            font-size: 11px;
            color: var(--text-muted);
            max-width: 80px;
            white-space: normal;
        }

        .action-buttons {
            display: flex;
            gap: 4px;
            justify-content: center;
        }

        .btn-view,
        .btn-edit,
        .btn-delete {
            border: none;
            background: transparent;
            color: #3498db;
            cursor: pointer;
            padding: 6px;
            border-radius: 4px;
            transition: all 0.2s;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-view:hover {
            background: rgba(52, 152, 219, 0.1);
            color: #2980b9;
        }

        .btn-edit:hover {
            background: rgba(241, 196, 15, 0.1);
            color: #f39c12;
        }

        .btn-delete:hover {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .pagination-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-card);
            border-radius: 10px;
            padding: 16px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .pagination-info {
            margin: 0;
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .pagination {
            display: flex;
            gap: 8px;
        }

        .pagination a,
        .pagination span {
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid var(--border-card);
            background: var(--bg-card);
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .pagination .active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 1024px) {
            .visits-table {
                font-size: 11px;
            }

            .visits-table td,
            .visits-table th {
                padding: 10px 8px;
            }

            .text-small {
                max-width: 60px;
                font-size: 10px;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
            }

            .btn-new-visit {
                width: 100%;
                justify-content: center;
            }

            .table-container {
                overflow-x: scroll;
            }

            .visits-table {
                font-size: 10px;
                min-width: 1200px;
            }

            .pagination-section {
                flex-direction: column;
                gap: 12px;
            }

            .pagination {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }

            .visits-table {
                font-size: 9px;
                min-width: 1400px;
            }

            .visits-table th,
            .visits-table td {
                padding: 8px 4px;
            }

            .action-buttons {
                gap: 2px;
            }

            .btn-view,
            .btn-edit,
            .btn-delete {
                padding: 4px;
                font-size: 12px;
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
<?php endif; ?>
<?php /**PATH C:\laragon\www\cmc_clinic\resources\views/clinic-visit/index.blade.php ENDPATH**/ ?>