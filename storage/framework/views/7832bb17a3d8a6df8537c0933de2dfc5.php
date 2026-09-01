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

     <?php $__env->slot('header', null, []); ?> Clinic Staff Details <?php $__env->endSlot(); ?>

    <div class="clinic-staff-show-page">
        <!-- Back Button -->
        <a href="<?php echo e(route('clinic-staff.index')); ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Staff List
        </a>

        <!-- Staff Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar"><?php echo e(substr($staff->name, 0, 1)); ?></div>
                <div class="profile-info">
                    <h1 class="profile-name"><?php echo e($staff->name); ?></h1>
                    <p class="profile-role"><?php echo e($staff->getRoleLabel()); ?></p>
                    <div class="profile-badges">
                        <span class="badge <?php echo e($staff->getApprovalStatusBadgeClass()); ?>">
                            <?php echo e($staff->getApprovalStatusLabel()); ?>

                        </span>
                        <span class="badge <?php echo e($staff->getActiveStatusBadgeClass()); ?>">
                            <?php echo e($staff->getActiveStatusLabel()); ?>

                        </span>
                    </div>
                </div>
            </div>

            <div class="profile-stats">
                <div class="p-stat">
                    <h3>Total Patients</h3>
                    <p class="p-stat-value"><?php echo e($stats['total_patients']); ?></p>
                </div>
                <div class="p-stat">
                    <h3>Total Visits</h3>
                    <p class="p-stat-value"><?php echo e($stats['total_visits']); ?></p>
                </div>
                <div class="p-stat">
                    <h3>Last Login</h3>
                    <p class="p-stat-value">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['last_login']): ?>
                            <?php echo e($stats['last_login']->format('M d, Y H:i A')); ?>

                        <?php else: ?>
                            Never
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Patients Section -->
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-users"></i> Patients Recorded
                </h2>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($patients->count() > 0): ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Category</th>
                                <th>Year/Section</th>
                                <th>Visits</th>
                                <th>Last Visit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr>
                                    <td><?php echo e($patient->name); ?></td>
                                    <td>
                                        <span class="badge <?php echo e($patient->getCategoryBadgeClass()); ?>">
                                            <?php echo e($patient->getCategoryLabel()); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($patient->year_section ?? 'N/A'); ?></td>
                                    <td><?php echo e($patient->clinicVisits->count()); ?></td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($patient->clinicVisits->first()): ?>
                                            <?php echo e($patient->clinicVisits->first()->visit_date->format('M d, Y')); ?>

                                        <?php else: ?>
                                            -
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="btn-sm" title="View Patient">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    <?php echo e($patients->links()); ?>

                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>No patients recorded by this staff yet.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Activity Section -->
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-history"></i> Login Activity
                </h2>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activities->count() > 0): ?>
                <div class="activity-list">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="activity-details">
                                <p class="activity-action">
                                    Logged in
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->ip_address): ?>
                                        from <span class="activity-ip"><?php echo e($activity->ip_address); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                                <p class="activity-time">
                                    <?php echo e($activity->logged_in_at->format('F d, Y H:i A')); ?>

                                </p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->logged_out_at): ?>
                                    <p class="activity-logout">
                                        Logged out: <?php echo e($activity->logged_out_at->format('F d, Y H:i A')); ?>

                                    </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="activity-duration">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->logged_out_at): ?>
                                    <?php
                                        $duration = $activity->logged_in_at->diffInMinutes($activity->logged_out_at);
                                        $hours = floor($duration / 60);
                                        $minutes = $duration % 60;
                                    ?>
                                    <span class="duration-badge">
                                        <?php echo e($hours > 0 ? $hours . 'h ' : ''); ?><?php echo e($minutes); ?>m
                                    </span>
                                <?php else: ?>
                                    <span class="duration-badge active">Active</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                <div class="table-pagination">
                    <?php echo e($activities->links()); ?>

                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <p>No activity records found.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <style>
        .clinic-staff-show-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-input);
            color: var(--text-muted);
            border: 1px solid var(--border-input);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            width: fit-content;
        }

        .btn-back:hover {
            background: var(--border-input);
        }

        .profile-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-inner);
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .profile-role {
            margin: 4px 0 8px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .profile-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .p-stat {
            background: var(--bg-input);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }

        .p-stat h3 {
            margin: 0;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .p-stat-value {
            margin: 8px 0 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .section-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border-inner);
        }

        .section-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #3498db;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .data-table thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            background: var(--bg-input);
            border-bottom: 1px solid var(--border-inner);
        }

        .data-table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-inner);
            color: var(--text-body);
        }

        .data-table tbody tr:hover {
            background: var(--bg-input);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success {
            background: #d5f4e6;
            color: #27ae60;
        }

        .badge-danger {
            background: #fadbd8;
            color: #e74c3c;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
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

        .btn-sm {
            background: #3498db;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-sm:hover {
            background: #2980b9;
        }

        .table-pagination {
            padding: 16px 0 0;
            display: flex;
            justify-content: flex-end;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: var(--bg-input);
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dbeafe;
            color: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .activity-details {
            flex: 1;
        }

        .activity-action {
            margin: 0;
            font-size: 13px;
            color: var(--text-heading);
            font-weight: 600;
        }

        .activity-ip {
            color: #3498db;
            font-family: monospace;
        }

        .activity-time {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--text-muted);
        }

        .activity-logout {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--text-muted);
        }

        .activity-duration {
            flex-shrink: 0;
        }

        .duration-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: #d5f4e6;
            color: #27ae60;
        }

        .duration-badge.active {
            background: #fff3cd;
            color: #856404;
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 48px;
            opacity: 0.3;
            display: block;
            margin-bottom: 12px;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-badges {
                justify-content: center;
            }

            .activity-item {
                flex-direction: column;
                text-align: center;
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
<?php /**PATH C:\laragon\www\cmc_clinic\resources\views/users/clinic-staff-show.blade.php ENDPATH**/ ?>