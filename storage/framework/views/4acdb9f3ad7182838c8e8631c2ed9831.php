<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMC Clinic — Login</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap');
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'DM Sans',sans-serif; background:#f0f4f8;
            min-height:100vh; display:flex; align-items:center;
            justify-content:center; padding:16px;
        }
        .card {
            background:white; border-radius:20px;
            box-shadow:0 4px 24px rgba(0,0,0,.08);
            width:100%; max-width:380px; padding:36px 32px;
        }
        .logo-wrap { display:flex; flex-direction:column; align-items:center; margin-bottom:24px; }
        .logo-wrap img { width:80px; height:80px; object-fit:contain; margin-bottom:10px; }
        .logo-title { font-size:18px; font-weight:700; color:#0f172a; text-align:center; }
        .logo-sub   { font-size:13px; color:#6b7280; text-align:center; margin-top:2px; }

        .alert {
            padding:14px 16px;
            border-radius:8px;
            display:flex;
            align-items:center;
            gap:12px;
            font-size:13px;
            margin-bottom:16px;
            animation:slideIn 0.3s ease-out;
        }
        .alert-error {
            background:#fef2f2;
            border:1px solid #fecaca;
            color:#dc2626;
        }
        .alert-success {
            background:#d5f4e6;
            color:#27ae60;
            border:1px solid #a9e6d2;
        }
        .alert-info {
            background:#e7f3ff;
            color:#0066cc;
            border:1px solid #b3d9ff;
        }
        .alert i {
            font-size:16px;
            flex-shrink:0;
        }
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .field { margin-bottom:14px; }
        .field label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:5px; }
        .input-wrap { position:relative; }
        .input-wrap input {
            width:100%; border:1px solid #e5e7eb; border-radius:10px;
            padding:10px 14px; font-size:13px; font-family:'DM Sans',sans-serif;
            color:#374151; background:#f9fafb; outline:none; transition:all .15s;
        }
        .input-wrap input.has-toggle { padding-right:42px; }
        .input-wrap input:focus {
            border-color:#2563eb; background:white;
            box-shadow:0 0 0 3px rgba(37,99,235,.08);
        }
        .input-wrap input.err { border-color:#ef4444; }
        .toggle-btn {
            position:absolute; right:12px; top:50%; transform:translateY(-50%);
            background:none; border:none; cursor:pointer; padding:4px;
            color:#9ca3af; display:flex; align-items:center; transition:color .15s;
        }
        .toggle-btn:hover { color:#374151; }
        .err-msg { font-size:11px; color:#ef4444; margin-top:4px; }

        .btn-submit {
            width:100%; background:#2563eb; color:white; border:none;
            border-radius:50px; padding:12px; font-size:14px; font-weight:600;
            font-family:'DM Sans',sans-serif; cursor:pointer; transition:background .2s; margin-top:6px;
        }
        .btn-submit:hover { background:#1d4ed8; }

        .footer-links { margin-top:18px; display:flex; flex-direction:column; gap:8px; align-items:center; }
        .footer-links a { font-size:13px; color:#6b7280; text-decoration:none; }
        .footer-links a span { color:#2563eb; font-weight:500; }
        .footer-links a:hover span { text-decoration:underline; }
        .forgot-link { font-size:12px; color:#9ca3af; }
        .forgot-link:hover { color:#2563eb !important; }
    </style>
</head>
<body>
<div class="card">

    <div class="logo-wrap">
        <img src="<?php echo e(asset('images/cmc-logo.png')); ?>" alt="CMC Logo">
        <div class="logo-title">Carmen Municipal College</div>
        <div class="logo-sub">School Clinic Management System</div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="alert alert-error" style="margin-bottom: 16px;">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
        <div class="alert alert-info" style="margin-bottom: 16px;">
            <i class="fas fa-hourglass-half"></i>
            <?php echo e(session('info')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('email_verified')): ?>
        <div class="alert alert-success" style="margin-bottom: 16px;">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Email Verified!</strong><br>
                Your account has been created and is now <strong>pending admin approval</strong>. You will be notified once approved.
            </div>
        </div>
    <?php elseif(session('pending_approval')): ?>
        <div class="alert alert-info" style="margin-bottom: 16px;">
            <i class="fas fa-hourglass-half"></i>
            <div>
                <strong>Account Pending Approval</strong><br>
                Your account is pending admin approval. You will be able to log in once the Clinic Nurse approves your account.
            </div>
        </div>
    <?php elseif(session('success')): ?>
        <div class="alert alert-success" style="margin-bottom: 16px;">
            <i class="fas fa-check-circle"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-error"><?php echo e($errors->first()); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('login')); ?>">
        <?php echo csrf_field(); ?>

        <div class="field">
            <label>Username</label>
            <div class="input-wrap">
                <input type="text"
                       name="username"
                       value="<?php echo e(old('username')); ?>"
                       placeholder="Enter your username"
                       class="<?php echo e($errors->has('username') ? 'err' : ''); ?>"
                       autofocus required>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="err-msg"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="field">
            <label>Password</label>
            <div class="input-wrap">
                <input type="password"
                       id="loginPw"
                       name="password"
                       placeholder="Enter your password"
                       class="has-toggle <?php echo e($errors->has('password') ? 'err' : ''); ?>"
                       required>
                <button type="button" class="toggle-btn"
                        onclick="togglePw('loginPw','loginPwIcon')"
                        tabindex="-1">
                    <svg id="loginPwIcon" width="18" height="18" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="err-msg"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Login</button>
    </form>

    <div class="footer-links">
        <a href="<?php echo e(route('register')); ?>">
            Don't have an account? <span>Register</span>
        </a>
        <a href="<?php echo e(route('password.request')); ?>"
           class="forgot-link">
            Forgot your password?
        </a>
    </div>

</div>

<script>
var eyeOpen   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
var eyeClosed = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';

function togglePw(inputId, iconId) {
    var input = document.getElementById(inputId);
    var icon  = document.getElementById(iconId);
    var show  = input.type === 'password';
    input.type = show ? 'text' : 'password';
    icon.innerHTML = show ? eyeOpen : eyeClosed;
}

// Auto-dismiss alerts after 6 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    });
}, 6000);
</script>
</body>
</html><?php /**PATH C:\laragon\www\cmc_clinic\resources\views/auth/login.blade.php ENDPATH**/ ?>