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

     <?php $__env->slot('header', null, []); ?> Client Consent Form <?php $__env->endSlot(); ?>

    <div class="form-container">
        <!-- Printable Section -->
        <div class="printable-form" id="printableForm">
            <!-- Header with Logo -->
            <div class="form-header">
                <div class="logo-section">
                    <img src="<?php echo e(asset('images/cmc-logo.png')); ?>" alt="CMC Logo" class="logo">
                </div>
                <div class="header-text">
                    <h2>Carmen Municipal College</h2>
                    <p>Carmen, Bohol</p>
                    <h3 class="form-title">CLIENT CONSENT FORM</h3>
                </div>
            </div>

            <form action="<?php echo e(route('forms.consent.store')); ?>" method="POST" id="consentForm">
                <?php echo csrf_field(); ?>

                <!-- Section I: Personal Information -->
                <div class="form-section">
                    <h4 class="section-header">I. PERSONAL INFORMATION</h4>
                    
                    <div class="form-row">
                        <div class="form-field full-width">
                            <label>Full Name</label>
                            <input type="text" name="full_name" class="form-input" value="<?php echo e(old('full_name')); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-input" value="<?php echo e(old('date_of_birth')); ?>">
                        </div>
                        <div class="form-field">
                            <label>Address</label>
                            <input type="text" name="address" class="form-input" value="<?php echo e(old('address')); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" class="form-input" value="<?php echo e(old('phone_number')); ?>">
                        </div>
                        <div class="form-field">
                            <label>Emergency Contact Name</label>
                            <input type="text" name="emergency_contact_name" class="form-input" value="<?php echo e(old('emergency_contact_name')); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field full-width">
                            <label>Emergency Contact Number</label>
                            <input type="text" name="emergency_contact_number" class="form-input" value="<?php echo e(old('emergency_contact_number')); ?>">
                        </div>
                    </div>
                </div>

                <!-- Section II: Consent for Treatment -->
                <div class="form-section">
                    <h4 class="section-header">II. CONSENT FOR TREATMENT</h4>
                    <div class="form-content">
                        <textarea name="consent_text" class="form-textarea" placeholder="I _________________, hereby consent to receive medical treatment and services at the Carmen Municipal College School Clinic."><?php echo e(old('consent_text')); ?></textarea>
                    </div>
                </div>

                <!-- Section III: Confidentiality -->
                <div class="form-section">
                    <h4 class="section-header">III. CONFIDENTIALITY</h4>
                    <div class="form-content">
                        <textarea name="confidentiality_text" class="form-textarea" placeholder="I understand that my medical information will be kept confidential."><?php echo e(old('confidentiality_text')); ?></textarea>
                    </div>
                </div>

                <!-- Section IV: Parent/Guardian Consent -->
                <div class="form-section">
                    <h4 class="section-header">IV. PARENT/GUARDIAN CONSENT (if applicable)</h4>
                    <div class="form-content">
                        <textarea name="guardian_text" class="form-textarea" placeholder="If the client is under 18 years of age, the parent or legal guardian must provide consent for treatment."><?php echo e(old('guardian_text')); ?></textarea>
                    </div>
                </div>

                <!-- Section V: Emergency Situations -->
                <div class="form-section">
                    <h4 class="section-header">V. EMERGENCY SITUATIONS</h4>
                    <div class="form-content">
                        <textarea name="emergency_text" class="form-textarea" placeholder="In the event of a medical emergency where I am unable to communicate, I authorize the clinic staff to provide necessary medical treatment as deemed appropriate by healthcare professionals."><?php echo e(old('emergency_text')); ?></textarea>
                    </div>
                </div>

                <!-- Section VI: Agreement -->
                <div class="form-section">
                    <h4 class="section-header">VI. AGREEMENT</h4>
                    <div class="form-content">
                        <textarea name="agreement_text" class="form-textarea" placeholder="I have read and understand the information provided in this consent form. I agree to receive medical treatment and services at the Carmen Municipal College."><?php echo e(old('agreement_text')); ?></textarea>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="signatures-section">
                    <div class="signature-block">
                        <p>CLIENT'S SIGNATURE OVER PRINTED NAME / DATE</p>
                        <div class="signature-line"></div>
                    </div>

                    <div class="signature-block">
                        <p>PARENT/GUARDIAN SIGNATURE (if applicable) / DATE</p>
                        <div class="signature-line"></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <a href="<?php echo e(route('forms.index')); ?>" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="button" class="btn btn-print" onclick="printForm()">
                        <i class="fas fa-print"></i> Print Form
                    </button>
                    <button type="submit" class="btn btn-save" id="submitBtn">
                        <i class="fas fa-save"></i> Save Form
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .form-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .printable-form {
            background: white;
            padding: 40px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-header {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .logo-section {
            margin-right: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .header-text h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #2d3e50;
        }

        .header-text p {
            margin: 2px 0;
            font-size: 13px;
            color: #95a5a6;
        }

        .form-title {
            margin: 10px 0 0 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3e50;
            letter-spacing: 1px;
        }

        .form-section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 12px;
        }

        .section-header {
            margin: 0 0 12px 0;
            font-size: 12px;
            font-weight: 700;
            color: #2d3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
        }

        .form-content {
            display: flex;
            flex-direction: column;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 12px;
        }

        .form-row.single {
            grid-template-columns: 1fr;
        }

        .form-field {
            display: flex;
            flex-direction: column;
        }

        .form-field.full-width {
            grid-column: 1 / -1;
        }

        .form-field label {
            font-size: 11px;
            font-weight: 700;
            color: #2d3e50;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .form-input,
        .form-textarea {
            border: 1px solid #999;
            padding: 8px;
            font-size: 12px;
            font-family: Arial, sans-serif;
            background: white;
            min-height: 24px;
        }

        .form-textarea {
            min-height: 60px;
            resize: none;
        }

        .signatures-section {
            margin-top: 30px;
        }

        .signature-block {
            margin-bottom: 20px;
        }

        .signature-block p {
            margin: 0 0 10px 0;
            font-size: 11px;
            font-weight: 700;
            color: #2d3e50;
            text-transform: uppercase;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            height: 40px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }

        .btn {
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cancel {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-cancel:hover {
            background: #d4d9e0;
        }

        .btn-print {
            background: #3498db;
            color: white;
        }

        .btn-print:hover {
            background: #2980b9;
        }

        .btn-save {
            background: #27ae60;
            color: white;
        }

        .btn-save:hover {
            background: #229954;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .form-actions {
                display: none;
            }

            .printable-form {
                border: none;
                box-shadow: none;
                padding: 20px;
            }

            .form-input,
            .form-textarea {
                border: 1px solid #999;
                background: white;
            }
        }

        @media (max-width: 768px) {
            .form-header {
                flex-direction: column;
            }

            .logo-section {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script>
        function printForm() {
            window.print();
        }

        document.getElementById('consentForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        });
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
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\forms\consent.blade.php ENDPATH**/ ?>