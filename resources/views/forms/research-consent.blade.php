<x-app-with-sidebar>
    <x-slot name="header">Research Data Consent</x-slot>

    <div class="form-container">
        @if (session('success'))
            <div class="form-success no-print">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="form-errors no-print">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('forms.research-consent.store') }}" method="POST" class="paper-form" id="research-consent-print-area">
            @csrf

            <div class="form-heading">
                <img src="{{ asset('images/cmc_background.jpg') }}" alt="Bayan ng Carmen Seal" onerror="this.style.visibility='hidden'">
                <div>
                    <h2>Republic of the Philippines</h2>
                    <p>Province of Bohol</p>
                    <strong>MUNICIPALITY OF CARMEN</strong><br>
                    <strong>CARMEN MUNICIPAL COLLEGE</strong><br>
                    <strong>COLLEGE CLINIC</strong>
                </div>
                <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo" onerror="this.style.visibility='hidden'">
            </div>

            <h3 class="document-title">Consent to Access Clinic and Personnel Data for Research</h3>

            <p>As a student of Carmen Municipal College, I hereby give my full consent for the <strong>faculty, staff, and administration</strong> of the institution to access and utilize my clinic records and relevant student data solely for <strong>educational research purposes</strong>. I understand that:</p>

            <ol>
                <li><strong>Confidentiality</strong> will be strictly observed, and all personal and medical information will be treated with utmost privacy and used only for legitimate research approved by the College.</li>
                <li>The data collected will not be used for any purpose other than research and academic improvement.</li>
                <li>My identity will be protected, and any data released or published will be <strong>anonymized</strong> to maintain confidentiality.</li>
                <li>I have the right to withdraw my consent at any time without any academic consequences.</li>
            </ol>

            <p>By signing below, I acknowledge that I have read and understood the purpose of this consent and voluntarily agree to participate.</p>

            <div class="signature-fields">
                <label>Personnel Name: <input type="text" name="personnel_name" value="{{ old('personnel_name', auth()->user()->name) }}" required></label>
                <label>Course/Year: <input type="text" name="course_year" value="{{ old('course_year') }}"></label>
                <label>Student ID Number: <input type="text" name="student_id" value="{{ old('student_id') }}"></label>
                <label>Signature: <input type="text" name="signature" value="{{ old('signature') }}"></label>
                <label>Date: <input type="date" name="consent_date" value="{{ old('consent_date', now()->format('Y-m-d')) }}" required></label>
            </div>

            <h4 class="witness-title">Witnessed by:</h4>
            <div class="signature-fields witness-fields">
                <label>Name: <input type="text" name="witness_name" value="{{ old('witness_name') }}"></label>
                <label>Position: <input type="text" name="witness_position" value="{{ old('witness_position') }}"></label>
                <label>Signature: <input type="text" name="witness_signature" value="{{ old('witness_signature') }}"></label>
                <label>Date: <input type="date" name="witness_date" value="{{ old('witness_date') }}"></label>
            </div>
        </form>

        <div class="form-actions no-print">
            <a href="{{ route('forms.index') }}">Cancel</a>
            <button type="button" onclick="window.print()">Print</button>
            <button type="submit" form="research-consent-print-area">Save Form</button>
        </div>
    </div>

    <style>
        .form-container {
            width: 100%;
            max-width: 8.5in;
            margin: auto;
        }

        .paper-form {
            width: 7.6in;
            min-height: 12.1in;
            margin: auto;
            background: #fff;
            padding: .34in;
            border: 1px solid #dbe3ec;
            box-shadow: 0 2px 8px #0001;
            color: #1e293b;
            font-family: "Courier New", monospace;
            font-size: 13px;
            line-height: 1.45;
            box-sizing: border-box;
        }

        .form-heading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 14px;
        }

        .form-heading img {
            width: 68px;
            height: 68px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .form-heading h2,
        .form-heading p {
            margin: 0;
        }

        .form-heading h2 { font-size: 16px; }
        .form-heading p { font-size: 13px; }
        .form-heading strong { font-size: 12px; }

        .document-title {
            text-align: center;
            text-transform: none;
            margin: 28px 0 20px;
            font-size: 14px;
        }

        .paper-form ol {
            margin: 16px 0;
            padding-left: 25px;
        }

        .paper-form ol li {
            padding-left: 4px;
            margin-bottom: 8px;
        }

        .signature-fields {
            margin-top: 24px;
        }

        .signature-fields label {
            display: flex;
            align-items: flex-end;
            gap: 6px;
            margin: 9px 0;
            font-weight: 700;
            font-size: 13px;
        }

        .paper-form input {
            flex: 1;
            min-width: 0;
            padding: 2px 3px;
            border: none;
            border-bottom: 1px solid #94a3b8;
            border-radius: 0;
            background: transparent;
            outline: none;
            font: inherit;
            font-weight: 400;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .paper-form input:focus {
            border-bottom-color: #1683b9;
        }

        .witness-title {
            margin: 44px 0 12px;
            font-size: 13px;
        }

        .witness-fields {
            margin-top: 0;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 16px;
            max-width: 8.5in;
            margin-left: auto;
            margin-right: auto;
        }

        .form-actions a,
        .form-actions button {
            padding: 9px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #fff;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
            color: #1e293b;
        }

        .form-actions button:last-child {
            background: #1683b9;
            border-color: #1683b9;
            color: #fff;
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

        @media (max-width: 700px) {
            .paper-form {
                width: 100%;
                min-height: 0;
                padding: 18px;
            }
            .form-heading img { width: 48px; height: 48px; }
            .signature-fields label { align-items: stretch; flex-direction: column; gap: 2px; }
            .form-actions { flex-wrap: wrap; }
        }

        /* ===== Print: only the consent sheet shows, fit to one page ===== */
        @media print {
            body * { visibility: hidden; }
            #research-consent-print-area, #research-consent-print-area * { visibility: visible; }
            #research-consent-print-area {
                position: absolute; top: 0; left: 0;
                width: 100% !important;
                min-height: auto;
                height: auto;
                margin: 0;
                padding: 0.3in;
                border: 1px solid #000;
                box-shadow: none;
            }
            .no-print { display: none !important; visibility: hidden !important; }

            @page { size: portrait; margin: 8mm; }

            .form-container { width: 100% !important; max-width: 100% !important; }
        }
    </style>
</x-app-with-sidebar>