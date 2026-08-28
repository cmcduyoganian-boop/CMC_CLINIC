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

     <?php $__env->slot('header', null, []); ?> Medicine Inventory <?php $__env->endSlot(); ?>

    <div class="medicines-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Medicine Inventory</h1>
                <p class="page-subtitle">View clinic medicine stock levels (Read-Only)</p>
            </div>
        </div>

        <!-- Alerts -->
        <div class="alerts-container">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lowStockCount > 0): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong><?php echo e($lowStockCount); ?></strong> medicine(s) with low stock
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiredCount > 0): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i>
                    <strong><?php echo e($expiredCount); ?></strong> expired medicine(s)
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Inventory Table -->
        <div class="table-card">
            <div class="table-wrapper">
                <table class="medicines-table">
                    <thead>
                        <tr>
                            <th>MEDICINE NAME</th>
                            <th>QUANTITY</th>
                            <th>UNIT</th>
                            <th>MIN STOCK</th>
                            <th>STATUS</th>
                            <th>EXPIRATION</th>
                            <th>LOCATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <tr>
                                <td><strong><?php echo e($medicine->name); ?></strong></td>
                                <td><?php echo e($medicine->quantity); ?></td>
                                <td><?php echo e($medicine->unit); ?></td>
                                <td><?php echo e($medicine->minimum_stock); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicine->quantity <= 0): ?>
                                        <span class="badge badge-out">Out of Stock</span>
                                    <?php elseif($medicine->quantity <= $medicine->minimum_stock): ?>
                                        <span class="badge badge-low">Low Stock</span>
                                    <?php else: ?>
                                        <span class="badge badge-good">Good Stock</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicine->expiration_date): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicine->isExpired()): ?>
                                            <span class="expired"><?php echo e($medicine->expiration_date->format('M d, Y')); ?></span>
                                        <?php else: ?>
                                            <?php echo e($medicine->expiration_date->format('M d, Y')); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td><?php echo e($medicine->storage_location); ?></td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <?php echo e($medicines->links()); ?>

            </div>
        </div>

        <!-- Note -->
        <div class="info-note">
            <i class="fas fa-info-circle"></i>
            <p>To request new medicines or manage stock, please contact the clinic nurse.</p>
        </div>
    </div>

    <style>
        .medicines-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
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

        .alerts-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            font-weight: 600;
        }

        .alert-warning {
            background: #ffeaa7;
            color: #d68910;
            border: 1px solid #ffc66d;
        }

        .alert-danger {
            background: #fadbd8;
            color: #c0392b;
            border: 1px solid #f5b7b1;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .medicines-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .medicines-table th {
            padding: 12px;
            text-align: left;
            background: #f9fafb;
            font-weight: 700;
            color: #2d3e50;
            border-bottom: 2px solid #e8ecf1;
            text-transform: uppercase;
            font-size: 11px;
        }

        .medicines-table td {
            padding: 12px;
            border-bottom: 1px solid #e8ecf1;
            color: #2d3e50;
        }

        .medicines-table tr:hover {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-good {
            background: #d5f4e6;
            color: #27ae60;
        }

        .badge-low {
            background: #ffeaa7;
            color: #d68910;
        }

        .badge-out {
            background: #fadbd8;
            color: #c0392b;
        }

        .expired {
            color: #c0392b;
            font-weight: 700;
        }

        .pagination-wrapper {
            padding: 16px 0;
            text-align: center;
        }

        .info-note {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #0066cc;
            font-size: 13px;
        }

        .info-note i {
            font-size: 18px;
        }

        .info-note p {
            margin: 0;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 22px;
            }

            .medicines-table {
                font-size: 10px;
            }

            .medicines-table th,
            .medicines-table td {
                padding: 8px;
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
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\inventory\medicines-view-only.blade.php ENDPATH**/ ?>