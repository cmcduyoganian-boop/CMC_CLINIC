<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Health Record</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 9px; color: #000; }
        .header { text-align: center; margin-bottom: 12px; }
        .header h1 { font-size: 16px; margin-bottom: 4px; }
        .header p { font-size: 9px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; table-layout: fixed; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; vertical-align: top; word-wrap: break-word; white-space: normal; overflow-wrap: break-word; }
        th { background: #f2f2f2; font-weight: 700; text-transform: uppercase; }
        tfoot td { background: #f2f2f2; font-weight: 700; border-top: 2px solid #000; border-bottom: 2px solid #000; }
        .footer { text-align: center; margin-top: 12px; font-size: 8px; color: #555; }

        .col-field { width: 15%; }
        .col-value { width: 35%; }
        .col-field2 { width: 15%; }
        .col-value2 { width: 35%; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Health Record</h1>
        <p>Student Code: {{ $data['studentCode'] ?? '' }} | Generated: {{ now()->format('F d, Y H:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="4" style="background:#ddd;font-size:12px;">I. STUDENT'S INFORMATION</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-field">Last Name</td>
                <td class="col-value">{{ $data['last_name'] ?? '' }}</td>
                <td class="col-field2">Suffix</td>
                <td class="col-value2">{{ $data['suffix'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">First Name</td>
                <td class="col-value">{{ $data['first_name'] ?? '' }}</td>
                <td class="col-field2">Maiden Name</td>
                <td class="col-value2">{{ $data['maiden_name'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">Middle Name</td>
                <td class="col-value">{{ $data['middle_name'] ?? '' }}</td>
                <td class="col-field2">Sex</td>
                <td class="col-value2">{{ $data['sex'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">Birthday</td>
                <td class="col-value">{{ $data['birthday'] ?? '' }}</td>
                <td class="col-field2">Birthplace</td>
                <td class="col-value2">{{ $data['birthplace'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">Blood Type</td>
                <td class="col-value">{{ $data['blood_type'] ?? '' }}</td>
                <td class="col-field2">Civil Status</td>
                <td class="col-value2">{{ $data['civil_status'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">Mother's Name</td>
                <td class="col-value">{{ $data['mother_name'] ?? '' }}</td>
                <td class="col-field2">Father's Name</td>
                <td class="col-value2">{{ $data['father_name'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">Residential Address</td>
                <td class="col-value" colspan="3">{{ $data['residential_address'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">Height</td>
                <td class="col-value">{{ $data['height'] ?? '' }}</td>
                <td class="col-field2">Weight</td>
                <td class="col-value2">{{ $data['weight'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">Course</td>
                <td class="col-value">{{ $data['course'] ?? '' }}</td>
                <td class="col-field2">Year &amp; Section</td>
                <td class="col-value2">{{ $data['year_section'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">Contact Number</td>
                <td class="col-value" colspan="3">{{ $data['contact_number'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="col-field">Spouse's Name</td>
                <td class="col-value" colspan="3">{{ $data['spouse_name'] ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="4" style="background:#ddd;font-size:12px;">II. PAST MEDICAL &amp; SURGICAL HISTORY</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" style="font-weight:bold;">PAST MEDICAL HISTORY</td>
            </tr>
            <tr>
                <td colspan="2" style="width:50%;">
                    @if(($data['pastMedicalHistory']['allergy'] ?? false))
                        Allergy{{ $data['pastMedicalHistory']['allergy_specify'] ? ': ' . $data['pastMedicalHistory']['allergy_specify'] : '' }}<br>
                    @endif
                    @if(($data['pastMedicalHistory']['asthma'] ?? false)) Asthma<br>@endif
                    @if(($data['pastMedicalHistory']['cancer'] ?? false)) Cancer<br>@endif
                    @if(($data['pastMedicalHistory']['cerebrovascular_disease'] ?? false)) Cerebrovascular Disease<br>@endif
                    @if(($data['pastMedicalHistory']['diabetes'] ?? false))
                        Diabetes Mellitus - maintenance{{ $data['pastMedicalHistory']['maintenance'] ? ': ' . $data['pastMedicalHistory']['maintenance'] : '' }}<br>
                    @endif
                    @if(($data['pastMedicalHistory']['epilepsy'] ?? false)) Epilepsy/Seizure Disorder<br>@endif
                    @if(($data['pastMedicalHistory']['emphysema'] ?? false)) Emphysema<br>@endif
                    @if(($data['pastMedicalHistory']['hepatitis'] ?? false))
                        Hepatitis{{ $data['pastMedicalHistory']['hepatitis_type'] ? ' (' . $data['pastMedicalHistory']['hepatitis_type'] . ')' : '' }}<br>
                    @endif
                    @if(($data['pastMedicalHistory']['hypertension'] ?? false)) Hypertension<br>@endif
                    @if(($data['pastMedicalHistory']['hyperlipidemia'] ?? false)) Hyperlipidemia<br>@endif
                    @if(($data['pastMedicalHistory']['peptic_ulcer'] ?? false)) Peptic Ulcer<br>@endif
                    @if(($data['pastMedicalHistory']['pneumonia'] ?? false)) Pneumonia<br>@endif
                    @if(($data['pastMedicalHistory']['thyroid_disease'] ?? false)) Thyroid Disease<br>@endif
                    @if(($data['pastMedicalHistory']['pulmonary_tb'] ?? false)) Pulmonary Tuberculosis<br>@endif
                    @if(($data['pastMedicalHistory']['urinary_tract_infection'] ?? false)) Urinary Tract Infection<br>@endif
                    @if(($data['pastMedicalHistory']['mental_illness'] ?? false)) Mental Illness<br>@endif
                    @if(($data['pastMedicalHistory']['others_medical'] ?? false))
                        Others{{ $data['pastMedicalHistory']['others_medical_specify'] ? ': ' . $data['pastMedicalHistory']['others_medical_specify'] : '' }}<br>
                    @endif
                    @if(($data['pastMedicalHistory']['none_medical'] ?? false)) None<br>@endif
                    @if(!array_filter($data['pastMedicalHistory'] ?? [])) None<br>@endif
                </td>
                <td colspan="2" style="width:50%;">
                    <strong>PAST SURGICAL HISTORY</strong><br><br>
                    @foreach($data['pastSurgicalHistory'] ?? [] as $surgery)
                        @if($surgery['operation'] ?? '')
                            {{ $surgery['operation'] }} - {{ $surgery['date'] ?? '' }}<br>
                        @endif
                    @endforeach
                    @if(!array_filter($data['pastSurgicalHistory'] ?? [])) None<br>@endif
                </td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="4" style="background:#ddd;font-size:12px;">FAMILY HISTORY</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" style="width:50%;">
                    @if(($data['familyHistory']['allergy'] ?? false))
                        Allergy{{ $data['familyHistory']['allergy_specify'] ? ': ' . $data['familyHistory']['allergy_specify'] : '' }}<br>
                    @endif
                    @if(($data['familyHistory']['asthma'] ?? false)) Asthma<br>@endif
                    @if(($data['familyHistory']['cancer'] ?? false)) Cancer<br>@endif
                    @if(($data['familyHistory']['cerebrovascular_disease'] ?? false)) Cerebrovascular Disease<br>@endif
                    @if(($data['familyHistory']['diabetes'] ?? false))
                        Diabetes Mellitus - maintenance{{ $data['familyHistory']['maintenance'] ? ': ' . $data['familyHistory']['maintenance'] : '' }}<br>
                    @endif
                    @if(($data['familyHistory']['epilepsy'] ?? false)) Epilepsy/Seizure Disorder<br>@endif
                    @if(($data['familyHistory']['emphysema'] ?? false)) Emphysema<br>@endif
                    @if(($data['familyHistory']['hepatitis'] ?? false))
                        Hepatitis{{ $data['familyHistory']['hepatitis_type'] ? ' (' . $data['familyHistory']['hepatitis_type'] . ')' : '' }}<br>
                    @endif
                    @if(($data['familyHistory']['hypertension'] ?? false)) Hypertension<br>@endif
                    @if(($data['familyHistory']['hyperlipidemia'] ?? false)) Hyperlipidemia<br>@endif
                    @if(($data['familyHistory']['peptic_ulcer'] ?? false)) Peptic Ulcer<br>@endif
                    @if(($data['familyHistory']['pneumonia'] ?? false)) Pneumonia<br>@endif
                    @if(($data['familyHistory']['thyroid_disease'] ?? false)) Thyroid Disease<br>@endif
                    @if(($data['familyHistory']['pulmonary_tb'] ?? false)) Pulmonary Tuberculosis<br>@endif
                    @if(($data['familyHistory']['urinary_tract_infection'] ?? false)) Urinary Tract Infection<br>@endif
                    @if(($data['familyHistory']['mental_illness'] ?? false)) Mental Illness<br>@endif
                    @if(($data['familyHistory']['others'] ?? false))
                        Others{{ $data['familyHistory']['others_specify'] ? ': ' . $data['familyHistory']['others_specify'] : '' }}<br>
                    @endif
                    @if(($data['familyHistory']['none'] ?? false)) None<br>@endif
                    @if(!array_filter($data['familyHistory'] ?? [])) None<br>@endif
                </td>
                <td colspan="2" style="width:50%;">
                    <strong>SIGNATURE</strong><br><br>
                    {{ $data['signature_name'] ?? '' }}<br>
                    Date: {{ $data['signature_date'] ?? '' }}<br><br>
                    <strong>Healthcare Provider:</strong><br>
                    {{ $data['healthcare_provider_name'] ?? '' }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Report generated from CMC Clinic Management System | {{ now()->format('F d, Y H:i A') }}</p>
    </div>
</body>
</html>
