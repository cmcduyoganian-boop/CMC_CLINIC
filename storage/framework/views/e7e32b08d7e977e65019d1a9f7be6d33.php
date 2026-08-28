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

     <?php $__env->slot('header', null, []); ?> Settings <?php $__env->endSlot(); ?>

    <div class="settings-page">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Settings & Profile</h1>
            <p class="page-subtitle">Manage your account and clinic settings</p>
        </div>

        <!-- Alert Messages -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert alert-success" id="successAlert">
                <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="alert alert-error" id="errorAlert">
                <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Settings Container -->
        <div class="settings-container">
            <!-- Sidebar Menu -->
            <div class="settings-sidebar">
                <div class="settings-menu">
                    <a href="#profile-section" class="menu-item active" onclick="switchTab(event, 'profile')">
                        <i class="fas fa-user"></i> Profile Information
                    </a>
                    <a href="#password-section" class="menu-item" onclick="switchTab(event, 'password')">
                        <i class="fas fa-lock"></i> Change Password
                    </a>
                    <a href="#username-section" class="menu-item" onclick="switchTab(event, 'username')">
                        <i class="fas fa-id-card"></i> Username
                    </a>
                    <a href="#clinic-section" class="menu-item" onclick="switchTab(event, 'clinic')">
                        <i class="fas fa-hospital"></i> Clinic Settings
                    </a>
                </div>

                <!-- User Info Card -->
                <div class="user-info-card">
                    <form action="<?php echo e(route('settings.avatar.update')); ?>" method="POST" enctype="multipart/form-data" id="avatarForm">
                        <?php echo csrf_field(); ?>
                        <label for="avatarInput" class="avatar-upload-wrap" title="Change profile picture">
                            <div class="user-avatar">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->getAvatarUrl()): ?>
                                    <img src="<?php echo e(auth()->user()->getAvatarUrl()); ?>" alt="<?php echo e(auth()->user()->name); ?>" id="avatarPreview">
                                <?php else: ?>
                                    <span id="avatarInitial"><?php echo e(auth()->user()->getInitial()); ?></span>
                                    <img src="" alt="" id="avatarPreview" style="display:none;">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="avatar-edit-badge">
                                <i class="fas fa-camera"></i>
                            </div>
                        </label>
                        <input type="file" name="avatar" id="avatarInput" accept="image/png, image/jpeg, image/webp" hidden>
                    </form>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->getAvatarUrl()): ?>
                        <form action="<?php echo e(route('settings.avatar.delete')); ?>" method="POST" id="avatarDeleteForm">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="remove-avatar-link">Remove photo</button>
                        </form>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="user-details">
                        <h3><?php echo e(auth()->user()->name); ?></h3>
                        <p class="role-badge"><?php echo e(auth()->user()->getRoleLabel()); ?></p>
                        <p class="status-badge <?php echo e(auth()->user()->getStatusBadgeClass()); ?>">
                            <?php echo e(ucfirst(auth()->user()->approval_status)); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="settings-content">

                <!-- ============ PROFILE SECTION ============ -->
                <div id="profile-section" class="settings-section active">
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-user"></i> Profile Information
                        </h2>
                        <p class="section-subtitle">Update your personal information</p>

                        <form action="<?php echo e(route('settings.profile.update')); ?>" method="POST" class="settings-form">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    class="form-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('name', auth()->user()->name)); ?>"
                                    required>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="form-error"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('email', auth()->user()->email)); ?>"
                                    required>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="form-error"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Phone Number *</label>
                                <input 
                                    type="text" 
                                    name="phone" 
                                    class="form-input <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('phone', auth()->user()->phone)); ?>"
                                    placeholder="09123456789"
                                    required>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="form-error"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Role</label>
                                <input 
                                    type="text" 
                                    class="form-input"
                                    value="<?php echo e(auth()->user()->getRoleLabel()); ?>"
                                    disabled>
                                <small class="form-hint">Your role cannot be changed. Contact administrator if needed.</small>
                            </div>

                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ============ PASSWORD SECTION ============ -->
                <div id="password-section" class="settings-section">
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-lock"></i> Change Password
                        </h2>
                        <p class="section-subtitle">Update your password to keep your account secure</p>

                        <!-- Step 1: Request OTP -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!session('step') || session('step') !== 'verify_otp'): ?>
                            <form action="<?php echo e(route('settings.password.request-otp')); ?>" method="POST" class="settings-form">
                                <?php echo csrf_field(); ?>

                                <div class="form-group">
                                    <label class="form-label" for="current_password">Current Password *</label>
                                    <div class="password-input-group">
                                        <input 
                                            type="password" 
                                            id="current_password"
                                            name="current_password" 
                                            class="form-input <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required
                                            placeholder="Enter your current password"
                                        >
                                        <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="form-error"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <button type="submit" class="btn btn-save">
                                    <i class="fas fa-envelope"></i> Send OTP to Email
                                </button>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Step 2: Verify OTP & Change Password -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('step') === 'verify_otp'): ?>
                            <form action="<?php echo e(route('settings.password.update')); ?>" method="POST" class="settings-form">
                                <?php echo csrf_field(); ?>

                                <div class="info-box">
                                    <i class="fas fa-info-circle"></i>
                                    <span>A verification code has been sent to your email. Enter it below to proceed with password change.</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="otp">Verification Code *</label>
                                    <input 
                                        type="text" 
                                        id="otp" 
                                        name="otp" 
                                        class="form-input otp-input <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        maxlength="6"
                                        placeholder="000000"
                                        pattern="[0-9]{6}"
                                        required
                                    >
                                    <small class="form-hint">Enter the 6-digit code from your email</small>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="form-error"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="password">New Password *</label>
                                    <div class="password-input-group">
                                        <input 
                                            type="password" 
                                            id="password"
                                            name="password" 
                                            class="form-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required
                                            placeholder="Enter new password"
                                        >
                                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-hint">Minimum 8 characters, must include uppercase, lowercase, number, and special character</small>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="form-error"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="password_confirmation">Confirm New Password *</label>
                                    <div class="password-input-group">
                                        <input 
                                            type="password" 
                                            id="password_confirmation"
                                            name="password_confirmation" 
                                            class="form-input <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            required
                                            placeholder="Confirm new password"
                                        >
                                        <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="form-error"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <button type="submit" class="btn btn-save">
                                    <i class="fas fa-lock"></i> Update Password
                                </button>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- ============ USERNAME SECTION ============ -->
                <div id="username-section" class="settings-section">
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-id-card"></i> Username
                        </h2>
                        <p class="section-subtitle">Change your login username</p>

                        <form action="<?php echo e(route('settings.username.update')); ?>" method="POST" class="settings-form">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label class="form-label">Current Username</label>
                                <input 
                                    type="text" 
                                    class="form-input"
                                    value="<?php echo e(auth()->user()->username); ?>"
                                    disabled>
                            </div>

                            <div class="form-divider"></div>

                            <div class="form-group">
                                <label class="form-label">New Username *</label>
                                <input 
                                    type="text" 
                                    name="username" 
                                    class="form-input <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Enter new username"
                                    required>
                                <small class="form-hint">Username must be unique and contain only letters, numbers, and underscores</small>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="form-error"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-check"></i> Update Username
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ============ CLINIC SECTION ============ -->
                <div id="clinic-section" class="settings-section">
                    <div class="section-card">
                        <h2 class="section-title">
                            <i class="fas fa-hospital"></i> Clinic Settings
                        </h2>
                        <p class="section-subtitle">Configure clinic information</p>

                        <form action="<?php echo e(route('settings.clinic.update')); ?>" method="POST" class="settings-form">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label class="form-label">Clinic Name</label>
                                <input 
                                    type="text" 
                                    name="clinic_name" 
                                    class="form-input"
                                    value="<?php echo e(old('clinic_name', auth()->user()->clinic_name)); ?>"
                                    placeholder="Carmen Municipal College School Clinic">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Clinic Phone</label>
                                <input 
                                    type="text" 
                                    name="clinic_phone" 
                                    class="form-input"
                                    value="<?php echo e(old('clinic_phone', auth()->user()->clinic_phone)); ?>"
                                    placeholder="09123456789">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Clinic Address</label>
                                <textarea 
                                    name="clinic_address" 
                                    class="form-textarea"
                                    rows="3"
                                    placeholder="Carmen, Bohol"><?php echo e(old('clinic_address', auth()->user()->clinic_address)); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Operating Hours</label>
                                <input 
                                    type="text" 
                                    name="clinic_hours" 
                                    class="form-input"
                                    value="<?php echo e(old('clinic_hours', auth()->user()->clinic_hours)); ?>"
                                    placeholder="Monday - Friday: 8:00 AM - 5:00 PM">
                            </div>

                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save"></i> Save Clinic Settings
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .settings-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
            padding-bottom: 40px;
        }

        .page-header {
            margin-bottom: 10px;
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

        .alert {
            padding: 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            animation: slideIn 0.3s ease-out;
        }

        .alert-success {
            background: #d5f4e6;
            color: #27ae60;
            border: 1px solid #a9e6d2;
        }

        .alert-error {
            background: #fadbd8;
            color: #c0392b;
            border: 1px solid #f5b7b1;
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

        .settings-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
        }

        .settings-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .settings-menu {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: #7f8c8d;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .menu-item:hover {
            background: #f9fafb;
            color: #3498db;
        }

        .menu-item.active {
            background: #f0f8ff;
            color: #3498db;
            border-left-color: #3498db;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .user-info-card {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border-radius: 10px;
            padding: 20px;
            color: white;
            text-align: center;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .avatar-upload-wrap {
            position: relative;
            display: inline-block;
            cursor: pointer;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.5);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .avatar-edit-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 28px;
            height: 28px;
            background: white;
            color: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.2s;
        }

        .avatar-upload-wrap:hover .avatar-edit-badge {
            background: #3498db;
            color: white;
            transform: scale(1.1);
        }

        .remove-avatar-link {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.85);
            font-size: 11px;
            text-decoration: underline;
            cursor: pointer;
            margin-bottom: 8px;
            padding: 0;
        }

        .remove-avatar-link:hover {
            color: white;
        }

        .user-details h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .role-badge {
            margin: 6px 0;
            font-size: 12px;
            opacity: 0.9;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: rgba(255,255,255,0.3);
            color: white;
        }

        .settings-content {
            display: flex;
            flex-direction: column;
        }

        .settings-section {
            display: none;
        }

        .settings-section.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .section-card {
            background: white;
            border-radius: 10px;
            padding: 28px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .section-title {
            margin: 0 0 8px 0;
            font-size: 20px;
            font-weight: 700;
            color: #2d3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #3498db;
        }

        .section-subtitle {
            margin: 0 0 24px 0;
            font-size: 13px;
            color: #95a5a6;
        }

        .settings-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #2d3e50;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .form-input,
        .form-textarea {
            border: 1px solid #e8ecf1;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
            font-family: 'Figtree', sans-serif;
            background: #f5f7fa;
            transition: all 0.2s;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            background: white;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-input.error,
        .form-textarea.error {
            border-color: #e74c3c;
            background: #fadbd8;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-error {
            font-size: 11px;
            color: #c0392b;
            margin-top: 4px;
            font-weight: 600;
        }

        .form-hint {
            font-size: 11px;
            color: #95a5a6;
            margin-top: 4px;
        }

        .form-divider {
            height: 1px;
            background: #e8ecf1;
            margin: 20px 0;
        }

        .password-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-group .form-input {
            padding-right: 40px;
            width: 100%;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #95a5a6;
            font-size: 16px;
            transition: all 0.2s;
            padding: 6px;
        }

        .toggle-password:hover {
            color: #3498db;
        }

        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            color: #0066cc;
            padding: 12px 16px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.5;
        }

        .info-box i {
            font-size: 16px;
            flex-shrink: 0;
        }

        .otp-input {
            font-size: 20px;
            letter-spacing: 8px;
            font-weight: 600;
            text-align: center;
            font-family: 'Courier New', monospace;
        }

        .form-input {
            padding-right: 40px;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-family: 'Figtree', sans-serif;
            width: fit-content;
        }

        .btn-save {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .btn-save:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .settings-container {
                grid-template-columns: 1fr;
            }

            .settings-sidebar {
                order: 2;
            }

            .settings-content {
                order: 1;
            }

            .settings-menu {
                display: flex;
                overflow-x: auto;
            }

            .menu-item {
                white-space: nowrap;
                flex: 1;
                border-left: none;
                border-bottom: 4px solid transparent;
            }

            .menu-item.active {
                border-left: none;
                border-bottom-color: #3498db;
            }

            .user-info-card {
                display: none;
            }

            .section-card {
                padding: 20px;
            }

            .page-title {
                font-size: 22px;
            }
        }

        @media (max-width: 480px) {
            .menu-item {
                padding: 12px 8px;
                font-size: 11px;
            }

            .menu-item span {
                display: none;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script>
        function switchTab(event, tabName) {
            event.preventDefault();

            // Hide all sections
            const sections = document.querySelectorAll('.settings-section');
            sections.forEach(section => {
                section.classList.remove('active');
            });

            // Remove active class from all menu items
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.classList.remove('active');
            });

            // Show selected section
            document.getElementById(tabName + '-section').classList.add('active');

            // Add active class to clicked menu item
            event.target.closest('.menu-item').classList.add('active');
        }

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = event.currentTarget;
            
            if (field.type === 'password') {
                field.type = 'text';
                button.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                field.type = 'password';
                button.innerHTML = '<i class="fas fa-eye"></i>';
            }
        }

        // AVATAR UPLOAD: preview instantly, then auto-submit
        (function () {
            const avatarInput = document.getElementById('avatarInput');
            const avatarForm = document.getElementById('avatarForm');
            const avatarPreview = document.getElementById('avatarPreview');
            const avatarInitial = document.getElementById('avatarInitial');

            if (!avatarInput) return;

            avatarInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                if (file.size > 2 * 1024 * 1024) {
                    alert('Image must be 2MB or smaller.');
                    avatarInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    avatarPreview.src = e.target.result;
                    avatarPreview.style.display = 'block';
                    if (avatarInitial) avatarInitial.style.display = 'none';
                };
                reader.readAsDataURL(file);

                avatarForm.submit();
            });
        })();

        // AVATAR REMOVE: confirm before deleting
        (function () {
            const deleteForm = document.getElementById('avatarDeleteForm');
            if (!deleteForm) return;

            deleteForm.addEventListener('submit', function (e) {
                if (!confirm('Remove your profile picture?')) {
                    e.preventDefault();
                }
            });
        })();

        // Auto-format OTP input
        window.addEventListener('load', function() {
            const otpInput = document.getElementById('otp');
            if (otpInput) {
                otpInput.addEventListener('keypress', function(e) {
                    if (!/[0-9]/.test(e.key)) {
                        e.preventDefault();
                    }
                });
            }

            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');

            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.animation = 'slideOut 0.3s ease-out forwards';
                    setTimeout(() => successAlert.remove(), 300);
                }, 5000);
            }

            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.animation = 'slideOut 0.3s ease-out forwards';
                    setTimeout(() => errorAlert.remove(), 300);
                }, 5000);
            }
        });

        // Add slideOut animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOut {
                to {
                    transform: translateY(-20px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c)): ?>
<?php $attributes = $__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c; ?>
<?php unset($__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c)): ?>
<?php $component = $__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c; ?>
<?php unset($__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\settings\index.blade.php ENDPATH**/ ?>