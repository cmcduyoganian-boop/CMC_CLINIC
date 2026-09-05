<div>
    <div class="student-health-record-page">
        <div class="form-header">
            <h1 class="form-title">Student Health Record</h1>
            <p class="form-description">Please fill out this form accurately. All information will be kept confidential.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0;padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="submit" class="form-container">
            <table class="clinic-header">
                <tr>
                    <td class="school-info">
                        <div class="school-brand">
                            <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2245%22 height=%2245%22><rect width=%2245%22 height=%2245%22 fill=%22%233498db%22/><text x=%2222.5%22 y=%2228%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2220%22>CMC</text></svg>'">
                            <div class="school-name">
                                <strong>Carmen Municipal College</strong>
                                <span>Carmen, Bohol</span>
                            </div>
                        </div>
                    </td>
                    <td class="student-code">
                        <strong>Student Code</strong>
                        <div class="code-boxes">
                            @for($i = 0; $i < 6; $i++)
                                <input type="text" maxlength="1" wire:model="studentCode.{{ $i }}" value="{{ $studentCode[$i] ?? '' }}">
                            @endfor
                        </div>
                    </td>
                </tr>
            </table>

            <div class="instructions">Instructions: Please print legibly and mark appropriate boxes with "✓"</div>

            <!-- I. STUDENT'S INFORMATION -->
            <table class="medical-table">
                <tr><th colspan="4" class="section-title">I. STUDENT'S INFORMATION</th></tr>
                <tr>
                    <td width="18%">Last Name</td>
                    <td width="32%"><input type="text" wire:model="last_name" required></td>
                    <td width="12%">Suffix</td>
                    <td width="38%"><input type="text" wire:model="suffix"></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type="text" wire:model="first_name" required></td>
                    <td colspan="2" style="font-size:10px;color:#666;">Please write Maiden Name if Married</td>
                </tr>
                <tr>
                    <td>Middle Name</td>
                    <td><input type="text" wire:model="middle_name"></td>
                    <td>Maiden Name</td>
                    <td><input type="text" wire:model="maiden_name"></td>
                </tr>
                <tr>
                    <td>Sex</td>
                    <td>
                        <select wire:model="sex" required>
                            <option value=""> </option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </td>
                    <td>Mother's Complete Name</td>
                    <td><input type="text" wire:model="mother_name"></td>
                </tr>
                <tr>
                    <td>Birthday (mm/dd/yy)</td>
                    <td><input type="text" wire:model="birthday" placeholder="mm/dd/yy"></td>
                    <td>Father's Complete Name</td>
                    <td><input type="text" wire:model="father_name"></td>
                </tr>
                <tr>
                    <td>Birthplace</td>
                    <td><input type="text" wire:model="birthplace"></td>
                    <td rowspan="2" style="font-weight:bold;text-align:center;">Civil Status</td>
                    <td rowspan="2" class="check-column">
                        <label><input type="radio" wire:model="civil_status" value="Single"> Single</label>
                        <label><input type="radio" wire:model="civil_status" value="Married"> Married</label>
                        <label><input type="radio" wire:model="civil_status" value="Annulled"> Annulled</label>
                        <label><input type="radio" wire:model="civil_status" value="Widowed"> Widowed</label>
                        <label><input type="radio" wire:model="civil_status" value="Separated"> Separated</label>
                        <label><input type="radio" wire:model="civil_status" value="Co-habitation"> Co-habitation</label>
                    </td>
                </tr>
                <tr>
                    <td>Blood Type</td>
                    <td><input type="text" wire:model="blood_type" maxlength="10"></td>
                </tr>
                <tr>
                    <td colspan="2">Residential Address</td>
                    <td colspan="2"><textarea wire:model="residential_address" rows="2"></textarea></td>
                </tr>
                <tr>
                    <td>Height</td>
                    <td><input type="text" wire:model="height"></td>
                    <td>Weight</td>
                    <td><input type="text" wire:model="weight"></td>
                </tr>
                <tr>
                    <td>Course</td>
                    <td><input type="text" wire:model="course"></td>
                    <td>Year &amp; Section</td>
                    <td><input type="text" wire:model="year_section"></td>
                </tr>
                <tr>
                    <td colspan="2">Contact Number</td>
                    <td colspan="2"><input type="text" wire:model="contact_number"></td>
                </tr>
                <tr>
                    <td colspan="2">Spouse's Name</td>
                    <td colspan="2"><input type="text" wire:model="spouse_name"></td>
                </tr>
            </table>

            <!-- II. PAST MEDICAL & SURGICAL HISTORY -->
            <table class="medical-table" style="margin-top:15px;">
                <tr><th colspan="4" class="section-title">II. PAST MEDICAL &amp; SURGICAL HISTORY</th></tr>
                <tr><th colspan="2">PAST MEDICAL HISTORY</th><th colspan="2">PAST MEDICAL HISTORY (cont'd)</th></tr>
                <tr>
                    <td class="check-column" width="40%">
                        <label><input type="checkbox" wire:model="pastMedicalHistory.allergy" value="1"> Allergy - please specify: <input type="text" wire:model="pastMedicalHistory.allergy_specify" class="line-input"></label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.asthma" value="1"> Asthma</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.cancer" value="1"> Cancer</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.cerebrovascular_disease" value="1"> Cerebrovascular Disease</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.diabetes" value="1"> Diabetes Mellitus - maintenance <input type="text" wire:model="pastMedicalHistory.maintenance" class="line-input" placeholder="Maintenance:"></label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.epilepsy" value="1"> Epilepsy/Seizure Disorder</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.emphysema" value="1"> Emphysema</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.hepatitis" value="1"> Hepatitis - please specify type: <input type="text" wire:model="pastMedicalHistory.hepatitis_type" class="line-input"></label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.hypertension" value="1"> Hypertension</label>
                    </td>
                    <td width="10%"></td>
                    <td class="check-column" width="40%">
                        <label><input type="checkbox" wire:model="pastMedicalHistory.hyperlipidemia" value="1"> Hyperlipidemia</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.peptic_ulcer" value="1"> Peptic Ulcer</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.pneumonia" value="1"> Pneumonia</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.thyroid_disease" value="1"> Thyroid Disease</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.pulmonary_tb" value="1"> Pulmonary Tuberculosis</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.urinary_tract_infection" value="1"> Urinary Tract Infection</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.mental_illness" value="1"> Mental Illness</label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.others_medical" value="1"> Others: <input type="text" wire:model="pastMedicalHistory.others_medical_specify" class="line-input"></label>
                        <label><input type="checkbox" wire:model="pastMedicalHistory.none_medical" value="1"> None</label>
                    </td>
                    <td width="10%"></td>
                </tr>
                <tr><td colspan="4" style="font-weight:bold;padding:8px;">PAST SURGICAL HISTORY</td></tr>
                <tr>
                    <th width="50%">OPERATION</th>
                    <th colspan="3">DATE (mm/dd/yy)</th>
                </tr>
                @foreach($pastSurgicalHistory as $index => $surgery)
                    <tr>
                        <td><input type="text" wire:model="pastSurgicalHistory.{{ $index }}.operation"></td>
                        <td colspan="3"><input type="text" wire:model="pastSurgicalHistory.{{ $index }}.date" placeholder="mm/dd/yy"></td>
                    </tr>
                @endforeach
            </table>

            <!-- FAMILY HISTORY -->
            <table class="medical-table" style="margin-top:15px;">
                <tr><th colspan="4" class="section-title">FAMILY HISTORY</th></tr>
                <tr><th colspan="2"></th><th colspan="2"></th></tr>
                <tr>
                    <td class="check-column" width="40%">
                        <label><input type="checkbox" wire:model="familyHistory.allergy" value="1"> Allergy - please specify: <input type="text" wire:model="familyHistory.allergy_specify" class="line-input"></label>
                        <label><input type="checkbox" wire:model="familyHistory.asthma" value="1"> Asthma</label>
                        <label><input type="checkbox" wire:model="familyHistory.cancer" value="1"> Cancer</label>
                        <label><input type="checkbox" wire:model="familyHistory.cerebrovascular_disease" value="1"> Cerebrovascular Disease</label>
                        <label><input type="checkbox" wire:model="familyHistory.diabetes" value="1"> Diabetes Mellitus - maintenance <input type="text" wire:model="familyHistory.maintenance" class="line-input" placeholder="Maintenance:"></label>
                        <label><input type="checkbox" wire:model="familyHistory.epilepsy" value="1"> Epilepsy/Seizure Disorder</label>
                        <label><input type="checkbox" wire:model="familyHistory.emphysema" value="1"> Emphysema</label>
                        <label><input type="checkbox" wire:model="familyHistory.hepatitis" value="1"> Hepatitis - please specify type: <input type="text" wire:model="familyHistory.hepatitis_type" class="line-input"></label>
                        <label><input type="checkbox" wire:model="familyHistory.hypertension" value="1"> Hypertension</label>
                    </td>
                    <td width="10%"></td>
                    <td class="check-column" width="40%">
                        <label><input type="checkbox" wire:model="familyHistory.hyperlipidemia" value="1"> Hyperlipidemia</label>
                        <label><input type="checkbox" wire:model="familyHistory.peptic_ulcer" value="1"> Peptic Ulcer</label>
                        <label><input type="checkbox" wire:model="familyHistory.pneumonia" value="1"> Pneumonia</label>
                        <label><input type="checkbox" wire:model="familyHistory.thyroid_disease" value="1"> Thyroid Disease</label>
                        <label><input type="checkbox" wire:model="familyHistory.pulmonary_tb" value="1"> Pulmonary Tuberculosis</label>
                        <label><input type="checkbox" wire:model="familyHistory.urinary_tract_infection" value="1"> Urinary Tract Infection</label>
                        <label><input type="checkbox" wire:model="familyHistory.mental_illness" value="1"> Mental Illness</label>
                        <label><input type="checkbox" wire:model="familyHistory.others" value="1"> Others: <input type="text" wire:model="familyHistory.others_specify" class="line-input"></label>
                        <label><input type="checkbox" wire:model="familyHistory.none" value="1"> None</label>
                    </td>
                    <td width="10%"></td>
                </tr>
            </table>

            <!-- SIGNATURE -->
            <table class="signature-table">
                <tr>
                    <th width="50%">SIGNATURE OVER PRINTED NAME/DATE</th>
                    <th width="50%">NAME OF HEALTHCARE PROVIDER</th>
                </tr>
                <tr>
                    <td>
                        <input type="text" wire:model="signature_name" placeholder="Student Signature" style="width:100%;border:none;border-bottom:1px solid #4b5563;padding:4px;">
                        <div class="signature-caption">SIGNATURE OVER PRINTED NAME/DATE</div>
                        <input type="date" wire:model="signature_date" style="width:100%;border:none;padding:4px;font-size:11px;">
                    </td>
                    <td>
                        <input type="text" wire:model="healthcare_provider_name" placeholder="Healthcare Provider Name" style="width:100%;border:none;border-bottom:1px solid #4b5563;padding:4px;">
                        <div class="signature-caption">NAME OF HEALTHCARE PROVIDER</div>
                    </td>
                </tr>
            </table>

            <div class="form-actions">
                <button type="button" wire:click="$refresh">Clear</button>
                <button type="submit" class="btn-primary">Save Medical Form</button>
                <button type="button" wire:click="exportPdf" class="btn-secondary">Export to PDF</button>
            </div>
        </form>
    </div>

    <style>
        .student-health-record-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .form-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .form-description {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border: 1px solid #d1d5db;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            max-width: 8.5in;
            margin: 0 auto;
        }

        .clinic-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .clinic-header td {
            border: 1px solid #4b5563;
            padding: 6px 8px;
            vertical-align: middle;
        }

        .school-info {
            width: 65%;
            padding: 6px 10px;
        }

        .school-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .school-brand img {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }

        .school-name {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .school-name strong {
            font-size: 13px;
        }

        .school-name span {
            font-size: 10px;
            color: #555;
        }

        .student-code {
            width: 35%;
            text-align: center;
            padding: 6px;
        }

        .student-code strong {
            display: block;
            font-size: 10px;
            margin-bottom: 4px;
        }

        .code-boxes {
            display: flex;
            justify-content: center;
            gap: 3px;
        }

        .code-boxes input {
            width: 22px !important;
            height: 22px !important;
            min-width: 22px !important;
            padding: 0 !important;
            border: 1px solid #4b5563 !important;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }

        .instructions {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            padding: 4px;
            border: 1px solid #4b5563;
            background: #f9fafb;
        }

        .medical-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .medical-table td,
        .medical-table th {
            border: 1px solid #4b5563;
            padding: 4px 6px;
            vertical-align: middle;
        }

        .section-title {
            background: #f2f2f2;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            font-size: 13px;
        }

        .medical-table input,
        .medical-table select,
        .medical-table textarea {
            width: 100%;
            min-width: 0;
            border: none;
            border-bottom: none;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
            padding: 2px 3px;
            font: inherit;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .medical-table textarea {
            resize: vertical;
            min-height: 40px;
        }

        .check-column {
            width: 40%;
        }

        .check-column label {
            display: block;
            line-height: 1.4;
            font-size: 11px;
        }

        .check-column input[type="checkbox"],
        .check-column input[type="radio"] {
            width: 12px !important;
            height: 12px !important;
            margin-right: 4px;
            vertical-align: middle;
        }

        .line-input {
            display: inline-block;
            width: 60%;
            margin-left: 4px;
            border-bottom: 1px solid #4b5563 !important;
            padding: 1px 2px !important;
        }

        .signature-table {
            margin-top: 15px;
            width: 100%;
        }

        .signature-table th {
            text-align: center;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #4b5563;
            background: #f9fafb;
        }

        .signature-table td {
            height: 50px;
            border: 1px solid #4b5563;
            vertical-align: bottom;
            padding: 0 8px 8px;
        }

        .signature-line {
            border-bottom: 1px solid #4b5563;
            height: 40px;
            margin-bottom: 4px;
        }

        .signature-caption {
            font-size: 9px;
            text-align: center;
            font-weight: bold;
            margin-top: 4px;
        }

        .form-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-primary {
            padding: 10px 24px;
            background: #1683b9;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #126896;
        }

        .btn-secondary {
            padding: 10px 24px;
            background: #27ae60;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #229954;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media print {
            @page {
                size: auto;
                margin: 0.5in;
            }

            body, html {
                width: 100%;
                margin: 0;
                padding: 0;
                background: #fff !important;
            }

            .form-container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
            }

            .clinic-header,
            .medical-table,
            .signature-table {
                width: 100% !important;
                table-layout: fixed;
                border-collapse: collapse;
            }

            .form-actions {
                display: none !important;
            }

            * {
                box-sizing: border-box;
            }
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 10px;
            }
        }
    </style>
</div>
