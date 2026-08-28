<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <div class="verify-email-container">
        <div class="verify-email-card">
            <div class="card-header">
                <i class="fas fa-envelope"></i>
                <h1>Verify Your Email</h1>
                <p><?php echo e(auth()->user()->name); ?></p>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('resent')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Success!</strong>
                        A fresh verification link has been sent to your email address.
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="info-section">
                <i class="fas fa-info-circle"></i>
                <p>We sent a verification link to your email address. Please check your email and click the verification link to confirm your identity and activate your account.</p>
            </div>

            <!-- Email Verification Instructions -->
            <div class="instructions-card">
                <h3>What to do next:</h3>
                <ol>
                    <li>Check your email inbox (and spam folder)</li>
                    <li>Click the verification link</li>
                    <li>Return here or log in to your dashboard</li>
                </ol>
            </div>

            <!-- Resend Verification Email Form -->
            <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="verify-form">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-redo"></i> Resend Verification Email
                </button>
            </form>

            <!-- Logout Link -->
            <p class="text-center form-footer">
                <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                    Log out and use another account
                </a>
                <form id="logoutForm" method="POST" action="<?php echo e(route('logout')); ?>" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
            </p>
        </div>
    </div>

    <style>
        .verify-email-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8fbff 0%, #e6f2ff 100%);
            padding: 20px;
        }

        .verify-email-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .card-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .card-header i {
            font-size: 48px;
            color: #3498db;
            margin-bottom: 16px;
            display: block;
        }

        .card-header h1 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 700;
            color: #1a3a52;
        }

        .card-header p {
            margin: 0;
            font-size: 13px;
            color: #7f8c8d;
        }

        .alert {
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            font-size: 12px;
            line-height: 1.6;
        }

        .alert i {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-success {
            background: #d1fae5;
            color: #27ae60;
            border: 1px solid #a9dfcd;
        }

        .info-section {
            background: #e7f3ff;
            border-left: 4px solid #3498db;
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            font-size: 13px;
            color: #0066cc;
        }

        .info-section i {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .info-section p {
            margin: 0;
            line-height: 1.6;
        }

        .instructions-card {
            background: #f9fafb;
            border: 1px solid #e8ecf1;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .instructions-card h3 {
            margin: 0 0 12px 0;
            font-size: 13px;
            font-weight: 700;
            color: #1a3a52;
        }

        .instructions-card ol {
            margin: 0;
            padding-left: 20px;
            font-size: 12px;
            color: #2d3e50;
            line-height: 1.8;
        }

        .instructions-card li {
            margin-bottom: 6px;
        }

        .verify-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
        }

        .btn-block {
            width: 100%;
        }

        .form-footer {
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            margin: 16px 0 0 0;
        }

        .form-footer a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .verify-email-card {
                padding: 24px;
            }

            .card-header h1 {
                font-size: 22px;
            }

            .card-header i {
                font-size: 40px;
            }
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\auth\verify-email.blade.php ENDPATH**/ ?>