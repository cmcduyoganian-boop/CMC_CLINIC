<x-app-with-sidebar>
    <x-slot name="header">Clinic Visit Log</x-slot>

    <div class="form-container">
        @if (session('success'))
            <div class="form-success no-print">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="form-errors no-print">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('forms.clinic-visit.store') }}" method="POST" class="paper-form" id="clinic-visit-print-area">
            @csrf

            <div class="form-heading">
                <img src="{{ asset('images/cmc_background.jpg') }}" alt="Left Seal" onerror="this.style.visibility='hidden'">
                <div>
                    <h2>Carmen Municipal College</h2>
                    <p>Carmen, Bohol</p>
                    <h3>CLINIC VISIT LOG</h3>
                </div>
                <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo" onerror="this.style.visibility='hidden'">
            </div>

            <div class="visit-table-wrap">
                <table class="visit-log-table">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>AGE</th>
                            <th>VITAL SIGNS</th>
                            <th>COMPLAINTS</th>
                            <th>MANAGEMENT</th>
                            <th>DIAGNOSIS</th>
                            <th>PHYSICIAN/NURSE<br>SIGNATURE</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ===== Row 1: the actual fillable/savable entry ===== --}}
                        <tr>
                            <td><input type="date" name="visit_date" value="{{ old('visit_date', now()->format('Y-m-d')) }}" required></td>
                            <td><input type="number" name="age" value="{{ old('age') }}"></td>
                            <td class="vitals-cell">
                                <table class="vitals-subtable">
                                    <tr><td class="vs-label">T-</td><td><input type="number" step="0.1" name="temperature" value="{{ old('temperature') }}"></td></tr>
                                    <tr><td class="vs-label">PR-</td><td><input type="number" name="pulse_rate" value="{{ old('pulse_rate') }}"></td></tr>
                                    <tr><td class="vs-label">RR-</td><td><input type="number" name="respiratory_rate" value="{{ old('respiratory_rate') }}"></td></tr>
                                    <tr><td class="vs-label">BP-</td><td><input type="text" name="blood_pressure" value="{{ old('blood_pressure') }}"></td></tr>
                                    <tr><td class="vs-label">HT-</td><td><input type="number" step="0.01" name="height" value="{{ old('height') }}"></td></tr>
                                    <tr><td class="vs-label">WT-</td><td><input type="number" step="0.01" name="weight" value="{{ old('weight') }}"></td></tr>
                                    <tr><td class="vs-label">BMI-</td><td><input type="number" step="0.1" name="bmi" value="{{ old('bmi') }}"></td></tr>
                                    <tr><td class="vs-label">SpO2-</td><td><input type="number" step="0.1" name="spo2" value="{{ old('spo2') }}"></td></tr>
                                </table>
                            </td>
                            <td><textarea name="complaints">{{ old('complaints') }}</textarea></td>
                            <td><textarea name="management">{{ old('management') }}</textarea></td>
                            <td><textarea name="diagnosis">{{ old('diagnosis') }}</textarea></td>
                            <td><input type="text" name="signature" value="{{ old('signature', auth()->user()->name) }}"></td>
                        </tr>

                        {{-- ===== Rows 2-4: blank reference rows, matching the printed log sheet =====
                             Not part of the submission — for handwritten entries on a printed copy. --}}
                        @for ($i = 0; $i < 3; $i++)
                            <tr class="blank-row">
                                <td></td>
                                <td></td>
                                <td class="vitals-cell">
                                    <table class="vitals-subtable">
                                        <tr><td class="vs-label">T-</td><td></td></tr>
                                        <tr><td class="vs-label">PR-</td><td></td></tr>
                                        <tr><td class="vs-label">RR-</td><td></td></tr>
                                        <tr><td class="vs-label">BP-</td><td></td></tr>
                                        <tr><td class="vs-label">HT-</td><td></td></tr>
                                        <tr><td class="vs-label">WT-</td><td></td></tr>
                                        <tr><td class="vs-label">BMI-</td><td></td></tr>
                                        <tr><td class="vs-label">SpO2-</td><td></td></tr>
                                    </table>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </form>

        <div class="form-actions no-print">
            <a href="{{ route('forms.index') }}">Cancel</a>
            <button type="button" onclick="window.print()">Print</button>
            <button type="submit" form="clinic-visit-print-area">Save Form</button>
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
            padding: .28in;
            border: 1px solid #dbe3ec;
            box-shadow: 0 2px 8px #0001;
            box-sizing: border-box;
        }

        .form-heading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 24px;
            padding-bottom: 14px;
        }

        .form-heading img { width: 68px; height: 68px; object-fit: contain; }
        .form-heading h2, .form-heading h3, .form-heading p { margin: 0; }
        .form-heading h3 { margin-top: 8px; }

        .visit-table-wrap { overflow-x: auto; }

        .visit-log-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            color: #1e293b;
        }

        .visit-log-table th,
        .visit-log-table td {
            border: 1px solid #475569;
            padding: 0;
            vertical-align: top;
        }

        .visit-log-table th {
            height: 42px;
            padding: 8px 5px;
            background: #f8fafc;
            text-align: center;
            font-size: 10px;
        }

        .visit-log-table td > input,
        .visit-log-table td > textarea {
            width: 100%;
            min-height: 150px;
            padding: 8px;
            border: none;
            border-radius: 0;
            resize: vertical;
            font: inherit;
            background: transparent;
            outline: none;
            box-sizing: border-box;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .blank-row td > input,
        .blank-row td { min-height: 150px; }

        /* ── Stacked vital-signs sub-table (matches the paper's boxed T-/PR-/RR-/etc rows) ── */
        .vitals-cell { padding: 0 !important; }

        .vitals-subtable {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .vitals-subtable tr { border: none; }

        .vitals-subtable td {
            border: none;
            border-bottom: 1px solid #94a3b8;
            padding: 3px 4px;
            font-size: 10px;
            height: 20px;
        }

        .vitals-subtable tr:last-child td { border-bottom: none; }

        .vs-label {
            width: 34px;
            font-weight: 700;
            white-space: nowrap;
        }

        .vitals-subtable input {
            width: 100%;
            min-width: 0;
            min-height: 0 !important;
            border: none;
            padding: 0 2px;
            font: inherit;
            background: transparent;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
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
            color: #1e293b;
            font: inherit;
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
            .paper-form { width: 100%; min-height: 0; padding: 18px; }
            .visit-table-wrap { overflow-x: auto; }
            .form-actions { flex-wrap: wrap; }
        }

        /* ===== Print: only the log sheet shows, fit to one bond paper, no side overflow ===== */
        @media print {
            body * { visibility: hidden; }
            #clinic-visit-print-area, #clinic-visit-print-area * { visibility: visible; }
            #clinic-visit-print-area {
                position: absolute; top: 0; left: 0;
                width: 100% !important;
                min-height: auto;
                height: auto;
                margin: 0;
                padding: 0.3in;
                border: 1px solid #000;
                box-shadow: none;
                box-sizing: border-box;
            }
            .no-print { display: none !important; visibility: hidden !important; }

            @page { size: portrait; margin: 8mm; }

            .form-container { width: 100% !important; max-width: 100% !important; }
            .visit-log-table { width: 100% !important; table-layout: fixed; border-collapse: collapse; }
        }
    </style>
</x-app-with-sidebar>