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

     <?php $__env->slot('header', null, []); ?> Student Information Form <?php $__env->endSlot(); ?>

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
                    <h3 class="form-title">STUDENT INFORMATION FORM</h3>
                </div>
            </div>

            <form action="<?php echo e(route('forms.student-info.store')); ?>" method="POST" id="studentForm">
                <?php echo csrf_field(); ?>

                <!-- Section I: Student Information -->
                <div class="form-section">
                    <h4 class="section-header">I. STUDENT'S INFORMATION</h4>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-input" value="<?php echo e(old('last_name')); ?>">
                            <small>Suffix</small>
                            <input type="text" name="suffix" class="form-input" value="<?php echo e(old('suffix')); ?>">
                        </div>
                        <div class="form-field">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-input" value="<?php echo e(old('first_name')); ?>">
                            <small>Please write Maiden Name if Married</small>
                            <input type="text" name="maiden_name" class="form-input" value="<?php echo e(old('maiden_name')); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-input" value="<?php echo e(old('middle_name')); ?>">
                            <small>Mother's Complete Name</small>
                            <input type="text" name="mother_name" class="form-input" value="<?php echo e(old('mother_name')); ?>">
                        </div>
                        <div class="form-field">
                            <label>Sex</label>
                            <select name="sex" class="form-input">
                                <option value="">Select...</option>
                                <option value="Male" <?php echo e(old('sex') == 'Male' ? 'selected' : ''); ?>>Male</option>
                                <option value="Female" <?php echo e(old('sex') == 'Female' ? 'selected' : ''); ?>>Female</option>
                            </select>
                            <small>Father's Complete Name</small>
                            <input type="text" name="father_name" class="form-input" value="<?php echo e(old('father_name')); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label>Birthday (mm/dd/yy)</label>
                            <input type="text" name="birthday" class="form-input" placeholder="MM/DD/YY" value="<?php echo e(old('birthday')); ?>">
                            <small>Blood Type</small>
                            <input type="text" name="blood_type" class="form-input" value="<?php echo e(old('blood_type')); ?>">
                        </div>
                        <div class="form-field">
                            <label>Birthplace</label>
                            <input type="text" name="birthplace" class="form-input" value="<?php echo e(old('birthplace')); ?>">
                            <small></small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label>Civil Status</label>
                            <select name="civil_status" class="form-input">
                                <option value="">Select...</option>
                                <option value="Single" <?php echo e(old('civil_status') == 'Single' ? 'selected' : ''); ?>>Single</option>
                                <option value="Married" <?php echo e(old('civil_status') == 'Married' ? 'selected' : ''); ?>>Married</option>
                                <option value="Annulled" <?php echo e(old('civil_status') == 'Annulled' ? 'selected' : ''); ?>>Annulled</option>
                                <option value="Widowed" <?php echo e(old('civil_status') == 'Widowed' ? 'selected' : ''); ?>>Widowed</option>
                                <option value="Separated" <?php echo e(old('civil_status') == 'Separated' ? 'selected' : ''); ?>>Separated</option>
                                <option value="Co-habitation" <?php echo e(old('civil_status') == 'Co-habitation' ? 'selected' : ''); ?>>Co-habitation</option>
                            </select>
                            <small>Residential Address</small>
                            <input type="text" name="residential_address" class="form-input" value="<?php echo e(old('residential_address')); ?>">
                        </div>
                        <div class="form-field">
                            <small>Height</small>
                            <input type="text" name="height" class="form-input" value="<?php echo e(old('height')); ?>">
                            <small>Weight</small>
                            <input type="text" name="weight" class="form-input" value="<?php echo e(old('weight')); ?>">
                            <small>Course</small>
                            <input type="text" name="course" class="form-input" value="<?php echo e(old('course')); ?>">
                            <small>Year & Section</small>
                            <input type="text" name="year_section" class="form-input" value="<?php echo e(old('year_section')); ?>">
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-field">
                            <label>Contact Number</label>
                            <input type="text" name="contact_number" class="form-input" value="<?php echo e(old('contact_number')); ?>">
                        </div>
                        <div class="form-field">
                            <label>Spouse's Name</label>
                            <input type="text" name="spouse_name" class="form-input" value="<?php echo e(old('spouse_name')); ?>">
                        </div>
                    </div>
                </div>

                <!-- Section II: Past Medical & Surgical History -->
                <div class="form-section">
                    <h4 class="section-header">II. PAST MEDICAL & SURGICAL HISTORY</h4>
                    
                    <div class="medical-history-grid">
                        <div class="history-column">
                            <h5>PAST MEDICAL HISTORY</h5>
                            <div class="checkbox-item">
                                <input type="checkbox" name="allergy" value="yes"> 
                                <label>Allergy - please specify:</label>
                                <input type="text" name="allergy_specify" class="form-input small" value="<?php echo e(old('allergy_specify')); ?>">
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="asthma" value="yes"> Asthma
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="cancer" value="yes"> Cancer
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="cerebrovascular_disease" value="yes"> Cerebrovascular Disease
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="diabetes" value="yes"> Diabetes Mellitus - maintenance
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="epilepsy" value="yes"> Epilepsy/Seizure Disorder
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="emphysema" value="yes"> Emphysema
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="hepatitis" value="yes"> 
                                <label>Hepatitis - please specify the type:</label>
                                <input type="text" name="hepatitis_type" class="form-input small" value="<?php echo e(old('hepatitis_type')); ?>">
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="hypertension" value="yes"> Hypertension
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="maintenance" value="yes"> Maintenance:
                            </div>
                        </div>

                        <div class="history-column">
                            <h5>MEDICAL HISTORY (cont'd)</h5>
                            <div class="checkbox-item">
                                <input type="checkbox" name="hyperlipidemia" value="yes"> Hyperlipidemia
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="peptic_ulcer" value="yes"> Peptic Ulcer
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="pneumonia" value="yes"> Pneumonia
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="thyroid_disease" value="yes"> Thyroid Disease
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="pulmonary_tb" value="yes"> Pulmonary Tuberculosis
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="urinary_tract_infection" value="yes"> Urinary Tract Infection
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="mental_illness" value="yes"> Mental Illness
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="others_medical" value="yes"> 
                                <label>Others</label>
                                <input type="text" name="others_medical_specify" class="form-input small" value="<?php echo e(old('others_medical_specify')); ?>">
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="none_medical" value="yes"> None
                            </div>
                        </div>
                    </div>

                    <div class="form-section-sub">
                        <h5>PAST SURGICAL HISTORY</h5>
                        <div class="surgery-table">
                            <div class="surgery-row">
                                <input type="text" name="operation_1" class="form-input" placeholder="Operation">
                                <input type="text" name="date_operation_1" class="form-input" placeholder="DATE (mm/dd/yy)">
                            </div>
                            <div class="surgery-row">
                                <input type="text" name="operation_2" class="form-input" placeholder="Operation">
                                <input type="text" name="date_operation_2" class="form-input" placeholder="DATE (mm/dd/yy)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section III: Family History -->
                <div class="form-section">
                    <h4 class="section-header">FAMILY HISTORY</h4>
                    
                    <div class="medical-history-grid">
                        <div class="history-column">
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_allergy" value="yes"> 
                                <label>Allergy - please specify:</label>
                                <input type="text" name="fam_allergy_specify" class="form-input small" value="<?php echo e(old('fam_allergy_specify')); ?>">
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_asthma" value="yes"> Asthma
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_cancer" value="yes"> Cancer
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_cerebrovascular" value="yes"> Cerebrovascular Disease
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_diabetes" value="yes"> Diabetes Mellitus - maintenance
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_epilepsy" value="yes"> Epilepsy/Seizure Disorder
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_emphysema" value="yes"> Emphysema
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_hepatitis" value="yes"> 
                                <label>Hepatitis - please specify the type:</label>
                                <input type="text" name="fam_hepatitis_type" class="form-input small" value="<?php echo e(old('fam_hepatitis_type')); ?>">
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_hypertension" value="yes"> Hypertension
                            </div>
                        </div>

                        <div class="history-column">
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_hyperlipidemia" value="yes"> Hyperlipidemia
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_peptic_ulcer" value="yes"> Peptic Ulcer
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_pneumonia" value="yes"> Pneumonia
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_thyroid_disease" value="yes"> Thyroid Disease
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_pulmonary_tb" value="yes"> Pulmonary Tuberculosis
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_urinary_tract" value="yes"> Urinary Tract Infection
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_mental_illness" value="yes"> Mental Illness
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_others" value="yes"> 
                                <label>Others</label>
                                <input type="text" name="fam_others_specify" class="form-input small" value="<?php echo e(old('fam_others_specify')); ?>">
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" name="fam_none" value="yes"> None
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Signatures -->
                <div class="signatures-section">
                    <div class="signature-row">
                        <div class="signature-block">
                            <p>SIGNATURE OVER PRINTED NAME/DATE</p>
                            <div class="signature-line"></div>
                        </div>
                        <div class="signature-block">
                            <p>NAME OF HEALTHCARE PROVIDER</p>
                            <div class="signature-line"></div>
                        </div>
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .printable-form {
            background: white;
            padding: 40px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
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
            font-size: 14px;
            font-weight: 700;
            color: #2d3e50;
            letter-spacing: 1px;
        }

        .form-section {
            margin-bottom: 20px;
            border: 1px solid #999;
            padding: 12px;
        }

        .section-header {
            margin: 0 0 12px 0;
            font-size: 11px;
            font-weight: 700;
            color: #2d3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .form-field label {
            font-size: 10px;
            font-weight: 700;
            color: #2d3e50;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .form-field small {
            font-size: 9px;
            color: #666;
            margin-top: 6px;
            margin-bottom: 2px;
        }

        .form-input {
            border: 1px solid #999;
            padding: 6px;
            font-size: 11px;
            font-family: Arial, sans-serif;
            background: white;
            margin-bottom: 6px;
        }

        .form-input.small {
            margin: 0;
        }

        .medical-history-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 12px 0;
        }

        .history-column h5 {
            margin: 0 0 12px 0;
            font-size: 10px;
            font-weight: 700;
            color: #2d3e50;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
        }

        .checkbox-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 8px;
            font-size: 10px;
        }

        .checkbox-item input[type="checkbox"] {
            margin-right: 6px;
            width: 14px;
            height: 14px;
        }

        .checkbox-item label {
            margin-left: 20px;
        }

        .form-section-sub {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .form-section-sub h5 {
            margin: 0 0 12px 0;
            font-size: 10px;
            font-weight: 700;
            color: #2d3e50;
            text-transform: uppercase;
        }

        .surgery-table {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .surgery-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .signatures-section {
            margin-top: 30px;
        }

        .signature-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .signature-block {
            display: flex;
            flex-direction: column;
        }

        .signature-block p {
            margin: 0 0 10px 0;
            font-size: 10px;
            font-weight: 700;
            color: #2d3e50;
            text-transform: uppercase;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            height: 30px;
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

            .form-input {
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

            .medical-history-grid {
                grid-template-columns: 1fr;
            }

            .signature-row {
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

        document.getElementById('studentForm').addEventListener('submit', function(e) {
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
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views/forms/student-info.blade.php ENDPATH**/ ?>