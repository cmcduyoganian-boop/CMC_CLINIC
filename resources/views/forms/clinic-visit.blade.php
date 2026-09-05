<x-app-with-sidebar>
    <x-slot name="header">Clinic Visit Log</x-slot>

    <div class="form-container">
        @if (session('success'))
            <div class="form-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('forms.clinic-visit.store') }}" method="POST" class="paper-form">
            @csrf
            <div class="form-heading">
                <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo">
                <div><h2>Carmen Municipal College</h2><p>Carmen, Bohol</p><h3>CLINIC VISIT LOG</h3></div>
            </div>
            <div class="visit-table-wrap">
                <table class="visit-log-table">
                    <thead><tr><th>DATE</th><th>AGE</th><th>VITAL SIGNS</th><th>COMPLAINTS</th><th>MANAGEMENT</th><th>DIAGNOSIS</th><th>PHYSICIAN/NURSE<br>SIGNATURE</th></tr></thead>
                    <tbody><tr>
                        <td><input type="date" name="visit_date" value="{{ old('visit_date', now()->format('Y-m-d')) }}" required></td>
                        <td><input type="number" name="age" value="{{ old('age') }}"></td>
                        <td class="vitals-cell">
                            <label>T° <input type="number" step="0.1" name="temperature" value="{{ old('temperature') }}"></label>
                            <label>PR <input type="number" name="pulse_rate" value="{{ old('pulse_rate') }}"></label>
                            <label>RR <input type="number" name="respiratory_rate" value="{{ old('respiratory_rate') }}"></label>
                            <label>BP <input type="text" name="blood_pressure" value="{{ old('blood_pressure') }}"></label>
                            <label>HT <input type="number" step="0.01" name="height" value="{{ old('height') }}"></label>
                            <label>WT <input type="number" step="0.01" name="weight" value="{{ old('weight') }}"></label>
                            <label>BMI <input type="number" step="0.1" name="bmi" value="{{ old('bmi') }}"></label>
                            <label>SpO2 <input type="number" step="0.1" name="spo2" value="{{ old('spo2') }}"></label>
                        </td>
                        <td><textarea name="complaints">{{ old('complaints') }}</textarea></td>
                        <td><textarea name="management">{{ old('management') }}</textarea></td>
                        <td><textarea name="diagnosis">{{ old('diagnosis') }}</textarea></td>
                        <td><input type="text" name="signature" value="{{ old('signature', auth()->user()->name) }}"></td>
                    </tr></tbody>
                </table>
            </div>
            @if ($errors->any()) <div class="form-errors">{{ $errors->first() }}</div> @endif
            <div class="form-actions"><a href="{{ route('forms.index') }}">Cancel</a><button type="button" onclick="window.print()">Print</button><button type="submit">Save Form</button></div>
        </form>
    </div>
    <style>
        @page{size:8.5in 13in;margin:.45in}.form-container{width:100%;max-width:8.5in;margin:auto}.paper-form{width:7.6in;min-height:12.1in;margin:auto;background:#fff;padding:.28in;border:1px solid #dbe3ec;box-shadow:0 2px 8px #0001}.form-heading{display:flex;align-items:center;justify-content:center;gap:18px;text-align:center;border-bottom:2px solid #333;margin-bottom:24px;padding-bottom:14px}.form-heading img{width:68px;height:68px;object-fit:contain}.form-heading h2,.form-heading h3,.form-heading p{margin:0}.form-heading h3{margin-top:8px}.visit-table-wrap{overflow-x:auto}.visit-log-table{width:100%;border-collapse:collapse;table-layout:fixed;color:#1e293b}.visit-log-table th,.visit-log-table td{border:1px solid #475569;padding:0;vertical-align:top}.visit-log-table th{height:42px;padding:8px 5px;background:#f8fafc;text-align:center;font-size:10px}        .visit-log-table td>input,.visit-log-table td>textarea{width:100%;min-height:150px;padding:8px;border:none;border-radius:0;resize:vertical;font:inherit;background:transparent;outline:none;appearance:none;-webkit-appearance:none;-moz-appearance:none}.visit-log-table td:has(input),.visit-log-table td:has(textarea){border-bottom:none!important}.vitals-cell label{display:flex;align-items:center;gap:4px;height:24px;padding:2px 4px;border-bottom:1px solid #94a3b8;font-size:10px}.vitals-cell input{width:100%;min-width:0;border:none;padding:2px;font:inherit;background:transparent;outline:none;appearance:none;-webkit-appearance:none;-moz-appearance:none}.form-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}.form-actions a,.form-actions button{padding:9px 15px;border:0;border-radius:6px;text-decoration:none;cursor:pointer}.form-actions button:last-child{background:#1683b9;color:#fff}.form-success{padding:12px;margin-bottom:16px;background:#e8f7ee;color:#157347}.form-errors{color:#b91c1c;margin-top:8px}@media(max-width:700px){.paper-form{width:100%;min-height:0;padding:18px}.visit-table-wrap{overflow-x:auto}.form-actions{flex-wrap:wrap}}        @media print {
            @page {
                size: auto;
                margin: 0.5in;
            }

            html, body {
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

            .form-container {
                width: 100% !important;
                max-width: none !important;
            }

            .paper-form {
                width: 100% !important;
                min-height: auto;
                height: auto;
                border: 1px solid #000;
                background: #fff;
                box-shadow: none;
                margin: 0 auto;
                padding: 0;
            }

            .visit-log-table {
                width: 100% !important;
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
