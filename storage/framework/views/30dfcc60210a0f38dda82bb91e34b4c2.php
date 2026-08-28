<div class="consent-forms-container">
    <!-- Buttons to Open Forms -->
    <div class="forms-buttons-grid">
        <button wire:click="openForm('client')" class="form-btn">
            📋 Client Consent Form
        </button>
        <button wire:click="openForm('research')" class="form-btn">
            🔬 Research Consent Form
        </button>
        <button wire:click="openForm('medical')" class="form-btn">
            🏥 Medical History Form
        </button>
    </div>

    <!-- Slide-Out Drawer Overlay -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isOpen): ?>
        <div class="drawer-overlay" wire:click="closeForm"></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Drawer -->
    <div class="drawer <?php echo e($isOpen ? 'drawer-open' : ''); ?>">
        <div class="drawer-header">
            <h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeForm === 'client'): ?>
                    📋 Client Consent Form
                <?php elseif($activeForm === 'research'): ?>
                    🔬 Research Consent Form
                <?php elseif($activeForm === 'medical'): ?>
                    🏥 Medical History Form
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </h2>
            <button wire:click="closeForm" class="drawer-close">✕</button>
        </div>

        <div class="drawer-content">
            <!-- CLIENT CONSENT FORM -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeForm === 'client'): ?>
                <form wire:submit="submitClientConsent">
                    <div class="form-section">
                        <h3>I. PERSONAL INFORMATION</h3>
                        
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" wire:model="fullName" class="form-input">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['fullName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Date of Birth *</label>
                                <input type="date" wire:model="dateOfBirth" class="form-input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['dateOfBirth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>Phone Number *</label>
                                <input type="tel" wire:model="phoneNumber" class="form-input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phoneNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Address *</label>
                            <textarea wire:model="address" rows="2" class="form-input"></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>II. EMERGENCY CONTACT</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Emergency Contact Name</label>
                                <input type="text" wire:model="emergencyContactName" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Emergency Contact Number</label>
                                <input type="tel" wire:model="emergencyContactNumber" class="form-input">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>III. CONSENT FOR TREATMENT</h3>
                        <label class="checkbox-group">
                            <input type="checkbox" wire:model="consentAgreed">
                            I hereby consent to receive medical treatment and services at Carmen Municipal College School Clinic.
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['consentAgreed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="button" wire:click="closeForm" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">✓ Submit Consent</button>
                    </div>
                </form>

            <!-- RESEARCH CONSENT FORM -->
            <?php elseif($activeForm === 'research'): ?>
                <form wire:submit="submitResearchConsent">
                    <div class="form-section">
                        <h3>Research Consent Information</h3>
                        <p class="form-text">
                            As a student of Carmen Municipal College, I hereby give my full consent to the faculty, staff, and administration 
                            to access and utilize my clinic records and relevant student data solely for educational research purposes.
                        </p>
                    </div>

                    <div class="form-section">
                        <h3>Student Information</h3>
                        
                        <div class="form-group">
                            <label>Personal Name *</label>
                            <input type="text" wire:model="studentName" class="form-input">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['studentName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Course/Year</label>
                                <input type="text" wire:model="courseYear" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Student ID Number *</label>
                                <input type="text" wire:model="studentId" class="form-input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['studentId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Confidentiality & Rights</h3>
                        <ul class="form-list">
                            <li>✓ Confidentiality will be strictly observed</li>
                            <li>✓ Data will not be used for other purposes</li>
                            <li>✓ Identity will be protected</li>
                            <li>✓ Right to withdraw consent at any time</li>
                        </ul>
                    </div>

                    <div class="form-section">
                        <label class="checkbox-group">
                            <input type="checkbox" wire:model="researchConsent">
                            I have read and understood the information provided. I agree to participate.
                        </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['researchConsent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="button" wire:click="closeForm" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">✓ Agree & Submit</button>
                    </div>
                </form>

            <!-- MEDICAL HISTORY FORM -->
            <?php elseif($activeForm === 'medical'): ?>
                <form wire:submit="submitMedicalHistory">
                    <div class="form-section">
                        <h3>I. STUDENT'S INFORMATION</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Last Name *</label>
                                <input type="text" wire:model="lastName" class="form-input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lastName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>First Name *</label>
                                <input type="text" wire:model="firstName" class="form-input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['firstName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" wire:model="middleName" class="form-input">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Sex *</label>
                                <select wire:model="sex" class="form-input">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['sex'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label>Date of Birth *</label>
                                <input type="date" wire:model="birthDate" class="form-input">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['birthDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="error-text"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Height (cm)</label>
                                <input type="number" wire:model="height" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Weight (kg)</label>
                                <input type="number" wire:model="weight" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Blood Type</label>
                                <select wire:model="bloodType" class="form-input">
                                    <option value="">Select</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Course</label>
                                <input type="text" wire:model="course" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="tel" wire:model="contactNumber" class="form-input">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>II. PAST MEDICAL & SURGICAL HISTORY</h3>
                        
                        <div class="checkbox-group-grid">
                            <label><input type="checkbox" wire:model="medicalHistory" value="Allergy"> Allergy</label>
                            <label><input type="checkbox" wire:model="medicalHistory" value="Asthma"> Asthma</label>
                            <label><input type="checkbox" wire:model="medicalHistory" value="Diabetes"> Diabetes</label>
                            <label><input type="checkbox" wire:model="medicalHistory" value="Hypertension"> Hypertension</label>
                            <label><input type="checkbox" wire:model="medicalHistory" value="Cancer"> Cancer</label>
                            <label><input type="checkbox" wire:model="medicalHistory" value="Heart Disease"> Heart Disease</label>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>III. FAMILY HISTORY</h3>
                        
                        <div class="checkbox-group-grid">
                            <label><input type="checkbox" wire:model="familyHistory" value="Diabetes"> Diabetes</label>
                            <label><input type="checkbox" wire:model="familyHistory" value="Cancer"> Cancer</label>
                            <label><input type="checkbox" wire:model="familyHistory" value="Hypertension"> Hypertension</label>
                            <label><input type="checkbox" wire:model="familyHistory" value="Heart Disease"> Heart Disease</label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" wire:click="closeForm" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">✓ Submit Medical History</button>
                    </div>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <style>
        /* ---- Consent Forms — theme-aware styles ---- */
        .consent-forms-container { padding: 0; }

        .forms-buttons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        .form-btn {
            padding: 20px;
            background: var(--bg-card);
            border: 2px solid var(--border-card);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-heading);
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        .form-btn:hover {
            border-color: #38bdf8;
            background: var(--bg-input);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(56,189,248,0.15);
        }

        /* Drawer Overlay */
        .drawer-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.55);
            z-index: 999;
            transition: opacity 0.3s;
        }

        /* Drawer Panel */
        .drawer {
            position: fixed;
            right: -500px;
            top: 0; bottom: 0;
            width: 500px;
            background: var(--bg-card);
            box-shadow: -4px 0 20px rgba(0,0,0,0.3);
            z-index: 1000;
            transition: right 0.3s ease;
            overflow-y: auto;
        }
        .drawer-open { right: 0; }

        .drawer-header {
            position: sticky;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-inner);
            background: var(--bg-card);
            z-index: 10;
        }
        .drawer-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
        }
        .drawer-close {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: var(--text-muted);
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.15s;
        }
        .drawer-close:hover {
            background: var(--bg-input);
            color: var(--text-heading);
        }

        .drawer-content { padding: 24px; }

        /* Form Sections */
        .form-section {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border-inner);
        }
        .form-section:last-of-type { border-bottom: none; }

        .form-section h3 {
            margin: 0 0 14px 0;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-text {
            font-size: 13px;
            color: var(--text-body);
            line-height: 1.6;
            margin: 0;
        }
        .form-list {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
            color: var(--text-body);
            line-height: 1.8;
            list-style: none;
        }
        .form-list li { padding: 2px 0; }

        /* Form Groups & Inputs */
        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            border-radius: 6px;
            color: var(--text-heading);
            padding: 10px 12px;
            font-size: 13px;
            font-family: inherit;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        .form-input:focus {
            border-color: #38bdf8;
            outline: none;
        }
        .error-text {
            font-size: 11px;
            color: #e74c3c;
            margin-top: 4px;
            display: block;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .form-row .form-group { margin-bottom: 0; }

        /* Checkboxes */
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 13px;
            color: var(--text-body);
            line-height: 1.6;
            margin-bottom: 14px;
            cursor: pointer;
        }
        .checkbox-group input { margin-top: 4px; cursor: pointer; accent-color: #38bdf8; }

        .checkbox-group-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .checkbox-group-grid label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-body);
            cursor: pointer;
        }
        .checkbox-group-grid input { cursor: pointer; accent-color: #38bdf8; }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding-top: 20px;
            border-top: 1px solid var(--border-inner);
            margin-top: 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            color: var(--text-body);
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            border-color: #38bdf8;
            color: var(--text-heading);
        }

        @media (max-width: 768px) {
            .drawer { width: 100%; right: -100%; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</div><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\livewire\forms\consent-forms.blade.php ENDPATH**/ ?>