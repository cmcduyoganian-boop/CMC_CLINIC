@php($d = $savedData ?? [])
@php($studentCode = old('student_code', $d['student_code'] ?? ''))
<x-app-with-sidebar>
    <x-slot name="header">Student Medical History Form</x-slot>
    <div class="no-print">
        <h1>Student Medical History Form</h1>
        <button onclick="window.print()">Print Form</button>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('forms.student-info.store') }}">
            @csrf
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
                                <input type="text" maxlength="1" name="student_code[]" value="{{ $studentCode[$i] ?? '' }}">
                            @endfor
                        </div>
                    </td>
                </tr>
            </table>

            <div class="instructions">Instructions: Please print legibly and mark appropriate boxes with "✓"</div>

            <table class="medical-table">
                <tr><th colspan="4" class="section-title">I. STUDENT'S INFORMATION</th></tr>
                <tr>
                    <td width="18%">Last Name</td>
                    <td width="32%"><input type="text" name="last_name" value="{{ old('last_name', $d['last_name'] ?? '') }}" required></td>
                    <td width="12%">Suffix</td>
                    <td width="38%"><input type="text" name="suffix" value="{{ old('suffix', $d['suffix'] ?? '') }}"></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type="text" name="first_name" value="{{ old('first_name', $d['first_name'] ?? '') }}" required></td>
                    <td colspan="2" style="font-size:10px;color:#666;">Please write Maiden Name if Married</td>
                </tr>
                <tr>
                    <td>Middle Name</td>
                    <td><input type="text" name="middle_name" value="{{ old('middle_name', $d['middle_name'] ?? '') }}"></td>
                    <td>Maiden Name</td>
                    <td><input type="text" name="maiden_name" value="{{ old('maiden_name', $d['maiden_name'] ?? '') }}"></td>
                </tr>
                <tr>
                    <td>Sex</td>
                    <td>
                        <select name="sex">
                            <option value=""> </option>
                            <option {{ old('sex', $d['sex'] ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option {{ old('sex', $d['sex'] ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </td>
                    <td>Mother's Complete Name</td>
                    <td><input type="text" name="mother_name" value="{{ old('mother_name', $d['mother_name'] ?? '') }}"></td>
                </tr>
                <tr>
                    <td>Birthday (mm/dd/yy)</td>
                    <td><input type="text" name="birthday" value="{{ old('birthday', $d['birthday'] ?? '') }}" placeholder="mm/dd/yy"></td>
                    <td>Father's Complete Name</td>
                    <td><input type="text" name="father_name" value="{{ old('father_name', $d['father_name'] ?? '') }}"></td>
                </tr>
                <tr>
                    <td>Birthplace</td>
                    <td><input type="text" name="birthplace" value="{{ old('birthplace', $d['birthplace'] ?? '') }}"></td>
                    <td rowspan="2" style="font-weight:bold;text-align:center;">Civil Status</td>
                    <td rowspan="2" class="check-column">
                        <label><input type="radio" name="civil_status" value="Single" {{ old('civil_status', $d['civil_status'] ?? '') === 'Single' ? 'checked' : '' }}> Single</label>
                        <label><input type="radio" name="civil_status" value="Married" {{ old('civil_status', $d['civil_status'] ?? '') === 'Married' ? 'checked' : '' }}> Married</label>
                        <label><input type="radio" name="civil_status" value="Annulled" {{ old('civil_status', $d['civil_status'] ?? '') === 'Annulled' ? 'checked' : '' }}> Annulled</label>
                        <label><input type="radio" name="civil_status" value="Widowed" {{ old('civil_status', $d['civil_status'] ?? '') === 'Widowed' ? 'checked' : '' }}> Widowed</label>
                        <label><input type="radio" name="civil_status" value="Separated" {{ old('civil_status', $d['civil_status'] ?? '') === 'Separated' ? 'checked' : '' }}> Separated</label>
                        <label><input type="radio" name="civil_status" value="Co-habitation" {{ old('civil_status', $d['civil_status'] ?? '') === 'Co-habitation' ? 'checked' : '' }}> Co-habitation</label>
                    </td>
                </tr>
                <tr>
                    <td>Blood Type</td>
                    <td><input type="text" name="blood_type" value="{{ old('blood_type', $d['blood_type'] ?? '') }}" maxlength="10"></td>
                </tr>
                <tr>
                    <td colspan="2">Residential Address</td>
                    <td colspan="2"><textarea name="residential_address" rows="2">{{ old('residential_address', $d['residential_address'] ?? '') }}</textarea></td>
                </tr>
                <tr>
                    <td>Height</td>
                    <td><input type="text" name="height" value="{{ old('height', $d['height'] ?? '') }}"></td>
                    <td>Weight</td>
                    <td><input type="text" name="weight" value="{{ old('weight', $d['weight'] ?? '') }}"></td>
                </tr>
                <tr>
                    <td>Course</td>
                    <td><input type="text" name="course" value="{{ old('course', $d['course'] ?? '') }}"></td>
                    <td>Year & Section</td>
                    <td><input type="text" name="year_section" value="{{ old('year_section', $d['year_section'] ?? '') }}"></td>
                </tr>
                <tr>
                    <td colspan="2">Contact Number</td>
                    <td colspan="2"><input type="text" name="contact_number" value="{{ old('contact_number', $d['contact_number'] ?? '') }}"></td>
                </tr>
                <tr>
                    <td colspan="2">Spouse's Name</td>
                    <td colspan="2"><input type="text" name="spouse_name" value="{{ old('spouse_name', $d['spouse_name'] ?? '') }}"></td>
                </tr>
            </table>

            <table class="medical-table" style="margin-top:15px;">
                <tr><th colspan="4" class="section-title">II. PAST MEDICAL & SURGICAL HISTORY</th></tr>
                <tr><th colspan="2">PAST MEDICAL HISTORY</th><th colspan="2">PAST MEDICAL HISTORY (cont'd)</th></tr>
                <tr>
                    <td class="check-column" width="40%">
                        <label><input type="checkbox" name="allergy" value="yes" {{ !empty($d['allergy']) ? 'checked' : '' }}> Allergy - please specify: <input type="text" name="allergy_specify" value="{{ old('allergy_specify', $d['allergy_specify'] ?? '') }}" class="line-input"></label>
                        <label><input type="checkbox" name="asthma" value="yes" {{ !empty($d['asthma']) ? 'checked' : '' }}> Asthma</label>
                        <label><input type="checkbox" name="cancer" value="yes" {{ !empty($d['cancer']) ? 'checked' : '' }}> Cancer</label>
                        <label><input type="checkbox" name="cerebrovascular_disease" value="yes" {{ !empty($d['cerebrovascular_disease']) ? 'checked' : '' }}> Cerebrovascular Disease</label>
                        <label><input type="checkbox" name="diabetes" value="yes" {{ !empty($d['diabetes']) ? 'checked' : '' }}> Diabetes Mellitus - maintenance <input type="text" name="maintenance" value="{{ old('maintenance', $d['maintenance'] ?? '') }}" class="line-input" placeholder="Maintenance:"></label>
                        <label><input type="checkbox" name="epilepsy" value="yes" {{ !empty($d['epilepsy']) ? 'checked' : '' }}> Epilepsy/Seizure Disorder</label>
                        <label><input type="checkbox" name="emphysema" value="yes" {{ !empty($d['emphysema']) ? 'checked' : '' }}> Emphysema</label>
                        <label><input type="checkbox" name="hepatitis" value="yes" {{ !empty($d['hepatitis']) ? 'checked' : '' }}> Hepatitis - please specify type: <input type="text" name="hepatitis_type" value="{{ old('hepatitis_type', $d['hepatitis_type'] ?? '') }}" class="line-input"></label>
                        <label><input type="checkbox" name="hypertension" value="yes" {{ !empty($d['hypertension']) ? 'checked' : '' }}> Hypertension</label>
                    </td>
                    <td width="10%"></td>
                    <td class="check-column" width="40%">
                        <label><input type="checkbox" name="hyperlipidemia" value="yes" {{ !empty($d['hyperlipidemia']) ? 'checked' : '' }}> Hyperlipidemia</label>
                        <label><input type="checkbox" name="peptic_ulcer" value="yes" {{ !empty($d['peptic_ulcer']) ? 'checked' : '' }}> Peptic Ulcer</label>
                        <label><input type="checkbox" name="pneumonia" value="yes" {{ !empty($d['pneumonia']) ? 'checked' : '' }}> Pneumonia</label>
                        <label><input type="checkbox" name="thyroid_disease" value="yes" {{ !empty($d['thyroid_disease']) ? 'checked' : '' }}> Thyroid Disease</label>
                        <label><input type="checkbox" name="pulmonary_tb" value="yes" {{ !empty($d['pulmonary_tb']) ? 'checked' : '' }}> Pulmonary Tuberculosis</label>
                        <label><input type="checkbox" name="urinary_tract_infection" value="yes" {{ !empty($d['urinary_tract_infection']) ? 'checked' : '' }}> Urinary Tract Infection</label>
                        <label><input type="checkbox" name="mental_illness" value="yes" {{ !empty($d['mental_illness']) ? 'checked' : '' }}> Mental Illness</label>
                        <label><input type="checkbox" name="others_medical" value="yes" {{ !empty($d['others_medical']) ? 'checked' : '' }}> Others: <input type="text" name="others_medical_specify" value="{{ old('others_medical_specify', $d['others_medical_specify'] ?? '') }}" class="line-input"></label>
                        <label><input type="checkbox" name="none_medical" value="yes" {{ !empty($d['none_medical']) ? 'checked' : '' }}> None</label>
                    </td>
                    <td width="10%"></td>
                </tr>
                <tr><td colspan="4" style="font-weight:bold;padding:8px;">PAST SURGICAL HISTORY</td></tr>
                <tr>
                    <th width="50%">OPERATION</th>
                    <th colspan="3">DATE (mm/dd/yy)</th>
                </tr>
                <tr>
                    <td><input type="text" name="operation_1" value="{{ old('operation_1', $d['operation_1'] ?? '') }}"></td>
                    <td colspan="3"><input type="text" name="date_operation_1" value="{{ old('date_operation_1', $d['date_operation_1'] ?? '') }}" placeholder="mm/dd/yy"></td>
                </tr>
                <tr>
                    <td><input type="text" name="operation_2" value="{{ old('operation_2', $d['operation_2'] ?? '') }}"></td>
                    <td colspan="3"><input type="text" name="date_operation_2" value="{{ old('date_operation_2', $d['date_operation_2'] ?? '') }}" placeholder="mm/dd/yy"></td>
                </tr>
                <tr>
                    <td><input type="text" name="operation_3" value="{{ old('operation_3', $d['operation_3'] ?? '') }}"></td>
                    <td colspan="3"><input type="text" name="date_operation_3" value="{{ old('date_operation_3', $d['date_operation_3'] ?? '') }}" placeholder="mm/dd/yy"></td>
                </tr>
            </table>

            <table class="medical-table" style="margin-top:15px;">
                <tr><th colspan="4" class="section-title">FAMILY HISTORY</th></tr>
                <tr><th colspan="2"></th><th colspan="2"></th></tr>
                <tr>
                    <td class="check-column" width="40%">
                        <label><input type="checkbox" name="fam_allergy" value="yes" {{ !empty($d['fam_allergy']) ? 'checked' : '' }}> Allergy - please specify: <input type="text" name="fam_allergy_specify" value="{{ old('fam_allergy_specify', $d['fam_allergy_specify'] ?? '') }}" class="line-input"></label>
                        <label><input type="checkbox" name="fam_asthma" value="yes" {{ !empty($d['fam_asthma']) ? 'checked' : '' }}> Asthma</label>
                        <label><input type="checkbox" name="fam_cancer" value="yes" {{ !empty($d['fam_cancer']) ? 'checked' : '' }}> Cancer</label>
                        <label><input type="checkbox" name="fam_cerebrovascular_disease" value="yes" {{ !empty($d['fam_cerebrovascular_disease']) ? 'checked' : '' }}> Cerebrovascular Disease</label>
                        <label><input type="checkbox" name="fam_diabetes" value="yes" {{ !empty($d['fam_diabetes']) ? 'checked' : '' }}> Diabetes Mellitus - maintenance <input type="text" name="fam_maintenance" value="{{ old('fam_maintenance', $d['fam_maintenance'] ?? '') }}" class="line-input" placeholder="Maintenance:"></label>
                        <label><input type="checkbox" name="fam_epilepsy" value="yes" {{ !empty($d['fam_epilepsy']) ? 'checked' : '' }}> Epilepsy/Seizure Disorder</label>
                        <label><input type="checkbox" name="fam_emphysema" value="yes" {{ !empty($d['fam_emphysema']) ? 'checked' : '' }}> Emphysema</label>
                        <label><input type="checkbox" name="fam_hepatitis" value="yes" {{ !empty($d['fam_hepatitis']) ? 'checked' : '' }}> Hepatitis - please specify type: <input type="text" name="fam_hepatitis_type" value="{{ old('fam_hepatitis_type', $d['fam_hepatitis_type'] ?? '') }}" class="line-input"></label>
                        <label><input type="checkbox" name="fam_hypertension" value="yes" {{ !empty($d['fam_hypertension']) ? 'checked' : '' }}> Hypertension</label>
                    </td>
                    <td width="10%"></td>
                    <td class="check-column" width="40%">
                        <label><input type="checkbox" name="fam_hyperlipidemia" value="yes" {{ !empty($d['fam_hyperlipidemia']) ? 'checked' : '' }}> Hyperlipidemia</label>
                        <label><input type="checkbox" name="fam_peptic_ulcer" value="yes" {{ !empty($d['fam_peptic_ulcer']) ? 'checked' : '' }}> Peptic Ulcer</label>
                        <label><input type="checkbox" name="fam_pneumonia" value="yes" {{ !empty($d['fam_pneumonia']) ? 'checked' : '' }}> Pneumonia</label>
                        <label><input type="checkbox" name="fam_thyroid_disease" value="yes" {{ !empty($d['fam_thyroid_disease']) ? 'checked' : '' }}> Thyroid Disease</label>
                        <label><input type="checkbox" name="fam_pulmonary_tb" value="yes" {{ !empty($d['fam_pulmonary_tb']) ? 'checked' : '' }}> Pulmonary Tuberculosis</label>
                        <label><input type="checkbox" name="fam_urinary_tract_infection" value="yes" {{ !empty($d['fam_urinary_tract_infection']) ? 'checked' : '' }}> Urinary Tract Infection</label>
                        <label><input type="checkbox" name="fam_mental_illness" value="yes" {{ !empty($d['fam_mental_illness']) ? 'checked' : '' }}> Mental Illness</label>
                        <label><input type="checkbox" name="fam_others" value="yes" {{ !empty($d['fam_others']) ? 'checked' : '' }}> Others: <input type="text" name="fam_others_specify" value="{{ old('fam_others_specify', $d['fam_others_specify'] ?? '') }}" class="line-input"></label>
                        <label><input type="checkbox" name="fam_none" value="yes" {{ !empty($d['fam_none']) ? 'checked' : '' }}> None</label>
                    </td>
                    <td width="10%"></td>
                </tr>
            </table>

            <table class="signature-table">
                <tr>
                    <th width="50%">SIGNATURE OVER PRINTED NAME/DATE</th>
                    <th width="50%">NAME OF HEALTHCARE PROVIDER</th>
                </tr>
                <tr>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-caption">SIGNATURE OVER PRINTED NAME/DATE</div>
                    </td>
                    <td>
                        <div class="signature-line"></div>
                        <div class="signature-caption">NAME OF HEALTHCARE PROVIDER</div>
                    </td>
                </tr>
            </table>

            <div class="form-actions">
                <button type="button" onclick="location.href='{{ route('forms.index') }}'">Clear</button>
                <button type="submit">Save Medical Form</button>
            </div>
        </form>
    </div>

    <style>
        .no-print {
            background: #fff;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .no-print h1 {
            font-size: 18px;
            color: #333;
        }

        .no-print button {
            padding: 10px 20px;
            background: #1683b9;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .no-print button:hover {
            background: #126896;
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

        .medical-table td:has(input),
        .medical-table td:has(select),
        .medical-table td:has(textarea) {
            border-bottom: none !important;
        }

        .check-column {
            width: 40%;
        }

        .check-column label {
            display: block;
            line-height: 1.4;
            font-size: 11px;
        }

        .check-column input[type="checkbox"] {
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
            text-align: right;
            gap: 10px;
        }

        .form-actions button {
            padding: 10px 24px;
            background: #1683b9;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .form-actions button:hover {
            background: #126896;
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

            .no-print {
                display: none !important;
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
</x-app-with-sidebar>
