<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMC Clinic — Register</title>
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
            width:100%; max-width:420px; padding:36px 32px;
            max-height:90vh; overflow-y:auto;
        }
        .logo-wrap { display:flex; flex-direction:column; align-items:center; margin-bottom:24px; }
        .logo-wrap img { width:80px; height:80px; object-fit:contain; margin-bottom:10px; }
        .logo-title { font-size:18px; font-weight:700; color:#0f172a; text-align:center; }
        .logo-sub   { font-size:13px; color:#6b7280; text-align:center; margin-top:2px; }

        .alert { padding:10px 14px; border-radius:10px; font-size:13px; margin-bottom:16px; }
        .alert-error { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }
        .alert-info { background:#f0fdf4; border:1px solid #86efac; color:#15803d; border-radius:10px; padding:10px 12px; margin-bottom:16px; font-size:12px; line-height:1.5; }

        .field { margin-bottom:12px; }
        .field label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:5px; }
        .input-wrap { position:relative; }
        .input-wrap input,
        .input-wrap select {
            width:100%; border:1px solid #e5e7eb; border-radius:10px;
            padding:10px 14px; font-size:13px; font-family:'DM Sans',sans-serif;
            color:#374151; background:#f9fafb; outline:none; transition:all .15s;
        }
        .input-wrap input.has-toggle { padding-right:42px; }
        .input-wrap input:focus,
        .input-wrap select:focus {
            border-color:#2563eb; background:white;
            box-shadow:0 0 0 3px rgba(37,99,235,.08);
        }
        .input-wrap input.err,
        .input-wrap select.err { border-color:#ef4444; }
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
            font-family:'DM Sans',sans-serif; cursor:pointer; transition:background .2s; margin-top:10px;
        }
        .btn-submit:hover { background:#1d4ed8; }

        .footer-links { margin-top:16px; text-align:center; }
        .footer-links a { font-size:13px; color:#6b7280; text-decoration:none; }
        .footer-links a span { color:#2563eb; font-weight:500; }
        .footer-links a:hover span { text-decoration:underline; }

        .card-desc { font-size:12px; color:#64748b; margin-bottom:16px; line-height:1.5; }

        .pwd-req { font-size:11px; color:#6b7280; margin-top:8px; padding:8px; background:#f9fafb; border-radius:6px; }
        .pwd-req li { list-style:none; margin:4px 0; }
        .pwd-req .ok { color:#10b981; }
        .pwd-req .fail { color:#ef4444; }

        .pwd-hint { font-size:11px; color:#7f8c8d; margin-top:4px; font-style:italic; }
    </style>
</head>
<body>
<div class="card">

    <div class="logo-wrap">
        <img src="<?php echo e(asset('images/cmc-logo.png')); ?>" alt="CMC Logo">
        <div class="logo-title">Carmen Municipal College</div>
        <div class="logo-sub">School Clinic Management System</div>
    </div>

    <p class="card-desc">
        Create your account to access the clinic management system. Your account will be approved by the clinic nurse before you can start using the system.
    </p>

    <!-- ✅ NEW: OTP VERIFICATION INFO -->
    <div class="alert alert-info">
        <strong>📧 Email Verification:</strong> After registration, you'll receive a 6-digit verification code via email. You must enter this code to complete your account setup.
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-error"><?php echo e($errors->first()); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e(route('register')); ?>" id="registerForm">
        <?php echo csrf_field(); ?>

        <!-- Full Name -->
        <div class="field">
            <label>Full Name *</label>
            <div class="input-wrap">
                <input type="text"
                       name="name"
                       value="<?php echo e(old('name')); ?>"
                       placeholder="Enter your full name"
                       class="<?php echo e($errors->has('name') ? 'err' : ''); ?>"
                       autofocus required>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="err-msg"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- ✅ NEW: Username Field -->
        <div class="field">
            <label>Username *</label>
            <div class="input-wrap">
                <input type="text"
                       name="username"
                       value="<?php echo e(old('username')); ?>"
                       placeholder="Create a username (for login)"
                       class="<?php echo e($errors->has('username') ? 'err' : ''); ?>"
                       required>
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

        <!-- Email Address -->
        <div class="field">
            <label>Email Address *</label>
            <div class="input-wrap">
                <input type="email"
                       name="email"
                       value="<?php echo e(old('email')); ?>"
                       placeholder="Enter your email"
                       class="<?php echo e($errors->has('email') ? 'err' : ''); ?>"
                       required>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="err-msg"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Phone Number -->
        <div class="field">
            <label>Phone Number *</label>
            <div class="input-wrap">
                <input type="tel"
                       name="phone"
                       value="<?php echo e(old('phone')); ?>"
                       placeholder="Enter your phone number"
                       class="<?php echo e($errors->has('phone') ? 'err' : ''); ?>"
                       required>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="err-msg"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- User Type/Role -->
        <div class="field">
            <label>User Type *</label>
            <div class="input-wrap">
                <select name="role"
                        class="<?php echo e($errors->has('role') ? 'err' : ''); ?>"
                        required>
                    <option value="">Select your role</option>
                    <option value="student" <?php echo e(old('role') === 'student' ? 'selected' : ''); ?>>Student</option>
                    <option value="faculty" <?php echo e(old('role') === 'faculty' ? 'selected' : ''); ?>>Faculty</option>
                    <option value="staff" <?php echo e(old('role') === 'staff' ? 'selected' : ''); ?>>Staff</option>
                    <option value="clinic_staff" <?php echo e(old('role') === 'clinic_staff' ? 'selected' : ''); ?>>Clinic Staff</option>
                </select>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="err-msg"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- ✅ UPDATED: Password with 6-8 char limit -->
        <div class="field">
            <label>Password *</label>
            <div class="input-wrap">
                <input type="password"
                       id="regPw"
                       name="password"
                       placeholder="Create a password (6-8 characters)"
                       class="has-toggle <?php echo e($errors->has('password') ? 'err' : ''); ?>"
                       maxlength="8"
                       onkeyup="checkPassword()"
                       required>
                <button type="button" class="toggle-btn"
                        onclick="togglePw('regPw','regPwIcon')"
                        tabindex="-1">
                    <svg id="regPwIcon" width="18" height="18" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <div class="pwd-hint">Password must be 6–8 characters with uppercase, lowercase, number, and special character.</div>
            <div class="pwd-req">
                <li id="pwd-length">❌ At least 6 characters</li>
                <li id="pwd-max">❌ Maximum 8 characters</li>
                <li id="pwd-upper">❌ Uppercase letter (A-Z)</li>
                <li id="pwd-lower">❌ Lowercase letter (a-z)</li>
                <li id="pwd-number">❌ Number (0-9)</li>
                <li id="pwd-special">❌ Special character (!@#$%^&*)</li>
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

        <!-- Confirm Password -->
        <div class="field">
            <label>Confirm Password *</label>
            <div class="input-wrap">
                <input type="password"
                       id="regConfirmPw"
                       name="password_confirmation"
                       placeholder="Confirm your password"
                       class="has-toggle <?php echo e($errors->has('password_confirmation') ? 'err' : ''); ?>"
                       maxlength="8"
                       required>
                <button type="button" class="toggle-btn"
                        onclick="togglePw('regConfirmPw','regConfirmPwIcon')"
                        tabindex="-1">
                    <svg id="regConfirmPwIcon" width="18" height="18" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="err-msg"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <button type="submit" class="btn-submit" id="registerSubmitBtn">
            <span class="submit-label">Verify Email</span>
            <span class="submit-loading" hidden>Creating account...</span>
        </button>
    </form>

    <div class="footer-links">
        <a href="<?php echo e(route('login')); ?>">
            Already have an account? <span>Login</span>
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

function checkPassword() {
    var pwd = document.getElementById('regPw').value;
    
    // Length checks
    document.getElementById('pwd-length').className = pwd.length >= 6 ? 'ok' : 'fail';
    document.getElementById('pwd-length').innerHTML = (pwd.length >= 6 ? '✓' : '❌') + ' At least 6 characters';
    
    document.getElementById('pwd-max').className = pwd.length <= 8 ? 'ok' : 'fail';
    document.getElementById('pwd-max').innerHTML = (pwd.length <= 8 ? '✓' : '❌') + ' Maximum 8 characters';
    
    // Character checks
    document.getElementById('pwd-upper').className = /[A-Z]/.test(pwd) ? 'ok' : 'fail';
    document.getElementById('pwd-upper').innerHTML = (/[A-Z]/.test(pwd) ? '✓' : '❌') + ' Uppercase letter (A-Z)';
    
    document.getElementById('pwd-lower').className = /[a-z]/.test(pwd) ? 'ok' : 'fail';
    document.getElementById('pwd-lower').innerHTML = (/[a-z]/.test(pwd) ? '✓' : '❌') + ' Lowercase letter (a-z)';
    
    document.getElementById('pwd-number').className = /[0-9]/.test(pwd) ? 'ok' : 'fail';
    document.getElementById('pwd-number').innerHTML = (/[0-9]/.test(pwd) ? '✓' : '❌') + ' Number (0-9)';
    
    document.getElementById('pwd-special').className = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(pwd) ? 'ok' : 'fail';
    document.getElementById('pwd-special').innerHTML = (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(pwd) ? '✓' : '❌') + ' Special character (!@#$%^&*)';
}

document.getElementById('registerForm').addEventListener('submit', function() {
    var button = document.getElementById('registerSubmitBtn');
    button.disabled = true;
    button.querySelector('.submit-label').hidden = true;
    button.querySelector('.submit-loading').hidden = false;
});
</script>
</body>
</html><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\auth\register.blade.php ENDPATH**/ ?>