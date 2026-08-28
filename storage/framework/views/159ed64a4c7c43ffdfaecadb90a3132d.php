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

    <div class="show-container">
        <div class="show-header">
            <div>
                <a href="<?php echo e(route('users.index')); ?>" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
                <h1>User Details</h1>
                <p>View user account information</p>
            </div>
            <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit User
            </a>
        </div>

        <!-- USER INFORMATION CARD -->
        <div class="info-card">
            <div class="info-section-title">Account Information</div>

            <div class="info-grid">
                <!-- Full Name -->
                <div class="info-item">
                    <label>Full Name</label>
                    <div class="info-value">
                        <?php echo e($user->name); ?>

                    </div>
                </div>

                <!-- Username -->
                <div class="info-item">
                    <label>Username</label>
                    <div class="info-value">
                        <code><?php echo e($user->username); ?></code>
                    </div>
                </div>

                <!-- Email -->
                <div class="info-item">
                    <label>Email Address</label>
                    <div class="info-value">
                        <a href="mailto:<?php echo e($user->email); ?>"><?php echo e($user->email); ?></a>
                    </div>
                </div>

                <!-- Phone -->
                <div class="info-item">
                    <label>Phone Number</label>
                    <div class="info-value">
                        <?php echo e($user->phone); ?>

                    </div>
                </div>

                <!-- Role -->
                <div class="info-item">
                    <label>Role</label>
                    <div class="info-value">
                        <span class="badge badge-info"><?php echo e($user->getRoleLabel()); ?></span>
                    </div>
                </div>

                <!-- Status -->
                <div class="info-item">
                    <label>Account Status</label>
                    <div class="info-value">
                        <span class="badge <?php echo e($user->getStatusBadgeClass()); ?>">
                            <?php echo e($user->getStatusLabel()); ?>

                        </span>
                    </div>
                </div>

                <!-- Created Date -->
                <div class="info-item">
                    <label>Account Created</label>
                    <div class="info-value">
                        <?php echo e($user->created_at->format('M d, Y \a\t g:i A')); ?>

                    </div>
                </div>

                <!-- Last Updated -->
                <div class="info-item">
                    <label>Last Updated</label>
                    <div class="info-value">
                        <?php echo e($user->updated_at->format('M d, Y \a\t g:i A')); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- PASSWORD STATUS -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->mustChangePassword()): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Password Change Required:</strong>
                    This user must change their temporary password on their next login.
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- CLINIC INFORMATION (if applicable) -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->clinic_name): ?>
            <div class="info-card">
                <div class="info-section-title">Clinic Information</div>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Clinic Name</label>
                        <div class="info-value">
                            <?php echo e($user->clinic_name); ?>

                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->clinic_phone): ?>
                        <div class="info-item">
                            <label>Clinic Phone</label>
                            <div class="info-value">
                                <?php echo e($user->clinic_phone); ?>

                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->clinic_address): ?>
                        <div class="info-item">
                            <label>Clinic Address</label>
                            <div class="info-value">
                                <?php echo e($user->clinic_address); ?>

                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->clinic_hours): ?>
                        <div class="info-item">
                            <label>Clinic Hours</label>
                            <div class="info-value">
                                <?php echo e($user->clinic_hours); ?>

                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- ACTIONS -->
        <div class="actions-card">
            <div class="actions-title">Actions</div>

            <div class="actions-grid">
                <!-- Edit -->
                <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="action-item edit">
                    <i class="fas fa-edit"></i>
                    <span>Edit User</span>
                </a>

                <!-- Reset Password -->
                <form method="POST" action="<?php echo e(route('users.reset-password', $user->id)); ?>" class="action-form" onsubmit="return confirm('Reset password for this user?');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="action-item reset">
                        <i class="fas fa-key"></i>
                        <span>Reset Password</span>
                    </button>
                </form>

                <!-- Approve (if pending) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->approval_status === 'pending'): ?>
                    <form method="POST" action="<?php echo e(route('users.approve', $user->id)); ?>" class="action-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="action-item approve">
                            <i class="fas fa-check"></i>
                            <span>Approve Account</span>
                        </button>
                    </form>

                    <form method="POST" action="<?php echo e(route('users.reject', $user->id)); ?>" class="action-form" onsubmit="return confirm('Reject this account?');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="action-item reject">
                            <i class="fas fa-times"></i>
                            <span>Reject Account</span>
                        </button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Disable (if approved) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->approval_status === 'approved' && auth()->id() !== $user->id): ?>
                    <form method="POST" action="<?php echo e(route('users.disable', $user->id)); ?>" class="action-form" onsubmit="return confirm('Disable this account?');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="action-item disable">
                            <i class="fas fa-lock"></i>
                            <span>Disable Account</span>
                        </button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Delete -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->id() !== $user->id): ?>
                    <form method="POST" action="<?php echo e(route('users.destroy', $user->id)); ?>" class="action-form" onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="action-item delete">
                            <i class="fas fa-trash"></i>
                            <span>Delete User</span>
                        </button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- BACK BUTTON -->
        <div class="back-section">
            <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <style>
        .show-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px;
        }

        .show-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e8ecf1;
            gap: 16px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #3498db;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .back-link:hover {
            gap: 10px;
        }

        .show-header h1 {
            margin: 0 0 6px 0;
            font-size: 32px;
            font-weight: 700;
            color: #1a3a52;
        }

        .show-header p {
            margin: 0;
            font-size: 13px;
            color: #7f8c8d;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
        }

        .btn-secondary {
            background: #ecf0f1;
            color: #2d3e50;
        }

        .btn-secondary:hover {
            background: #d5dbdb;
            transform: translateY(-2px);
        }

        /* INFO CARD */
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }

        .info-section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a3a52;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e8ecf1;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .info-item label {
            font-size: 11px;
            font-weight: 700;
            color: #2d3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 14px;
            color: #1a3a52;
            font-weight: 500;
            line-height: 1.5;
        }

        .info-value a {
            color: #3498db;
            text-decoration: none;
            transition: all 0.2s;
        }

        .info-value a:hover {
            text-decoration: underline;
        }

        .info-value code {
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #2d3e50;
        }

        /* BADGE */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
        }

        .badge-info {
            background: #dbeafe;
            color: #3498db;
        }

        .badge-success {
            background: #d1fae5;
            color: #27ae60;
        }

        .badge-warning {
            background: #ffefd5;
            color: #f39c12;
        }

        .badge-danger {
            background: #fee2e2;
            color: #e74c3c;
        }

        .badge-secondary {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        /* ALERT */
        .alert {
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert i {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-warning {
            background: #ffefd5;
            color: #f39c12;
            border: 1px solid #ffd9a8;
        }

        .alert-warning strong {
            display: block;
            margin-bottom: 4px;
        }

        /* ACTIONS */
        .actions-card {
            background: white;
            border-radius: 10px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }

        .actions-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a3a52;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e8ecf1;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
        }

        .action-item {
            padding: 14px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .action-item i {
            font-size: 20px;
        }

        .action-item.edit {
            background: #3498db;
        }

        .action-item.edit:hover {
            background: #2980b9;
        }

        .action-item.reset {
            background: #6366f1;
        }

        .action-item.reset:hover {
            background: #4f46e5;
        }

        .action-item.approve {
            background: #27ae60;
        }

        .action-item.approve:hover {
            background: #229954;
        }

        .action-item.reject {
            background: #f39c12;
        }

        .action-item.reject:hover {
            background: #e67e22;
        }

        .action-item.disable {
            background: #95a5a6;
        }

        .action-item.disable:hover {
            background: #7f8c8d;
        }

        .action-item.delete {
            background: #e74c3c;
        }

        .action-item.delete:hover {
            background: #c0392b;
        }

        .action-form {
            display: contents;
        }

        /* BACK SECTION */
        .back-section {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid #e8ecf1;
        }

        @media (max-width: 768px) {
            .show-container {
                padding: 16px;
            }

            .show-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .show-header h1 {
                font-size: 24px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
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
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\users\show.blade.php ENDPATH**/ ?>