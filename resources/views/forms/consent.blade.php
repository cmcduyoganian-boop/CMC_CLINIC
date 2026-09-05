<x-app-with-sidebar>
    <x-slot name="header">Client Consent Form</x-slot>

    <div class="consent-document-page">
        @if (session('success'))
            <div class="form-success">{{ session('success') }}</div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="form-errors">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('forms.consent.store') }}" method="POST" class="consent-document">
            @csrf

            <table class="header-table">
                <tr>
                    <td class="logo-cell" rowspan="2">
                        <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC logo" class="seal">
                    </td>
                    <td class="school-name">Carmen Municipal College</td>
                </tr>
                <tr>
                    <td class="location">Carmen, Bohol</td>
                </tr>
                <tr>
                    <th class="document-title" colspan="2">CLIENT CONSENT FORM</th>
                </tr>
            </table>

            <table class="form-table">
                <tr><th class="section-title" colspan="2">I. PERSONAL INFORMATION</th></tr>
                <tr>
                    <td class="label-cell">Full Name</td>
                    <td class="input-cell"><input type="text" name="full_name" value="{{ old('full_name') }}" required></td>
                </tr>
                <tr>
                    <td class="label-cell">Date of Birth</td>
                    <td class="input-cell"><input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"></td>
                </tr>
                <tr>
                    <td class="label-cell">Address</td>
                    <td class="input-cell"><input type="text" name="address" value="{{ old('address') }}"></td>
                </tr>
                <tr>
                    <td class="label-cell">Phone Number</td>
                    <td class="input-cell"><input type="text" name="phone_number" value="{{ old('phone_number') }}"></td>
                </tr>
                <tr>
                    <td class="label-cell">Emergency Contact Name</td>
                    <td class="input-cell"><input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"></td>
                </tr>
                <tr>
                    <td class="label-cell">Emergency Contact Number</td>
                    <td class="input-cell"><input type="text" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}"></td>
                </tr>

                <tr><th class="section-title" colspan="2">II. CONSENT FOR TREATMENT</th></tr>
                <tr>
                    <td class="paragraph-row" colspan="2">
                        I, <span class="fill-line"><input type="text" name="client_signature" value="{{ old('client_signature') }}" class="inline-signature-input"></span>, hereby consent to receive medical treatment and services at the Carmen Municipal College School Clinic.
                    </td>
                </tr>

                <tr><th class="section-title" colspan="2">III. CONFIDENTIALITY</th></tr>
                <tr>
                    <td class="paragraph-row" colspan="2">I understand that my medical information will be kept confidential.</td>
                </tr>

                <tr><th class="section-title" colspan="2">IV. PARENT/GUARDIAN CONSENT (if applicable)</th></tr>
                <tr>
                    <td class="paragraph-row" colspan="2">If the client is under 18 years of age, the parent or legal guardian must provide consent for treatment.</td>
                </tr>

                <tr><th class="section-title" colspan="2">V. EMERGENCY SITUATIONS</th></tr>
                <tr>
                    <td class="paragraph-row" colspan="2">In the event of a medical emergency where I am unable to communicate, I authorize the clinic staff to provide necessary medical treatment as deemed appropriate by healthcare professionals.</td>
                </tr>

                <tr><th class="section-title" colspan="2">VI. AGREEMENT</th></tr>
                <tr>
                    <td class="paragraph-row" colspan="2">I have read and understand the information provided in this consent form. I agree to receive medical treatment and services at the Carmen Municipal College.</td>
                </tr>
            </table>

            <table class="signatures">
                <tr>
                    <td>
                        <div class="signature-line"><input type="text" name="client_signature_date" value="{{ old('client_signature_date') }}" class="signature-input"></div>
                        <div class="signature-caption">CLIENT'S SIGNATURE OVER PRINTED NAME / DATE</div>
                    </td>
                    <td>
                        <div class="signature-line"><input type="text" name="guardian_signature" value="{{ old('guardian_signature') }}" class="signature-input"></div>
                        <div class="signature-caption">PARENT/GUARDIAN SIGNATURE (if applicable) / DATE</div>
                    </td>
                </tr>
            </table>

            <div class="form-actions">
                <a href="{{ route('forms.index') }}">Cancel</a>
                <button type="button" onclick="window.print()">Print Form</button>
                <button type="submit">Save Form</button>
            </div>
        </form>
    </div>

    <style>
        @page {
            size: Letter portrait;
            margin: 0.35in;
        }

        .consent-document-page {
            width: min(820px, 100%);
            margin: 0 auto;
        }

        .consent-document {
            width: 100%;
            background: #fff;
            border: 1px solid #111;
            padding: 18px;
            color: #111;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
        }

        .header-table,
        .form-table,
        .signatures {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header-table th,
        .header-table td,
        .form-table th,
        .form-table td,
        .signatures td {
            border: 1px solid #111;
            vertical-align: middle;
        }

        .header-table .logo-cell {
            width: 120px;
            height: 56px;
            text-align: center;
            vertical-align: middle;
            padding: 8px;
        }

        .seal {
            width: 52px;
            height: 52px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .school-name {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            padding: 8px;
        }

        .location {
            text-align: center;
            font-size: 13px;
            padding: 6px;
        }

        .document-title {
            padding: 12px 8px;
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            background: #fff;
        }

        .section-title {
            padding: 7px 9px;
            background: #f2f2f2;
            font-weight: 700;
            text-align: left;
        }

        .label-cell {
            width: 35%;
            font-weight: 600;
            padding: 8px 9px;
        }

        .input-cell {
            padding: 8px 9px;
        }

        .input-cell input,
        .inline-signature-input,
        .signature-input {
            width: 100%;
            border: none;
            background: transparent;
            font: inherit;
            padding: 2px 0;
            outline: none;
            box-sizing: border-box;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .input-cell,
        .signature-line {
            border-bottom: none;
            padding-bottom: 0;
        }

        .paragraph-row {
            min-height: 52px;
            line-height: 1.45;
            padding: 8px 9px;
        }

        .fill-line {
            display: inline-block;
            min-width: 180px;
            margin: 0 6px;
            vertical-align: bottom;
        }

        .signatures {
            margin-top: 18px;
            border: 0;
        }

        .signatures td {
            height: 128px;
            padding: 0 22px 8px;
            border: 0;
            vertical-align: bottom;
            text-align: center;
        }

        .signature-line {
            height: 64px;
            border-bottom: 1px solid #111;
            padding: 0;
        }

        .signature-caption {
            padding-top: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 16px;
        }

        .form-actions a,
        .form-actions button {
            padding: 8px 14px;
            border: 1px solid #111;
            background: #fff;
            cursor: pointer;
            font: inherit;
            text-decoration: none;
            color: #111;
        }

        .form-success {
            padding: 12px;
            margin-bottom: 16px;
            background: #e8f7ee;
            color: #157347;
            border-radius: 6px;
        }

        .form-errors {
            color: #b91c1c;
            margin-bottom: 12px;
            font-weight: 600;
        }

        @media (max-width: 640px) {
            .consent-document-page {
                width: 100%;
            }

            .consent-document {
                padding: 10px;
                border: 0;
            }

            .header-table .logo-cell {
                width: 90px;
            }

            .school-name {
                font-size: 14px;
            }

            .document-title {
                font-size: 17px;
            }

            .form-actions {
                display: flex;
                gap: 8px;
            }

            .form-actions a,
            .form-actions button {
                flex: 1;
                text-align: center;
            }
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
            }

            .clinic-sidebar,
            .sidebar-overlay,
            .app-topbar,
            .profile-popup {
                display: none !important;
            }

            .consent-document-page {
                width: 100% !important;
                max-width: 100% !important;
            }

            .consent-document {
                width: 100% !important;
                border: 1px solid #000;
                margin: 0;
                padding: 18px;
                background: #fff;
                box-shadow: none;
            }

            .header-table,
            .form-table,
            .signatures {
                width: 100%;
                table-layout: fixed;
                border-collapse: collapse;
            }

            .form-actions,
            .form-success,
            .form-errors {
                display: none !important;
            }

            * {
                box-sizing: border-box;
            }
        }
    </style>
</x-app-with-sidebar>
