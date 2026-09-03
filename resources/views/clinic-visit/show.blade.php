<x-app-with-sidebar>
    <x-slot name="header">Clinic Visit Details</x-slot>

    <div class="clinic-visit-show-page">
        <!-- Header -->
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">Visit Details</h1>
                <p class="page-subtitle">{{ $visit->visit_date->format('F d, Y') }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('clinic-visit.edit', $visit->id) }}" class="btn-edit-main">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('clinic-visit.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Patient Info Card -->
        <div class="info-card patient-card">
            <h2 class="card-title">Patient Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Full Name</label>
                    <span class="info-value">{{ $visit->patient->name }}</span>
                </div>
                <div class="info-item">
                    <label>Category</label>
                    <span class="info-value">
                        <span class="badge {{ $visit->patient->getCategoryBadgeClass() }}">
                            {{ $visit->patient->getCategoryLabel() }}
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <label>Year & Section</label>
                    <span class="info-value">{{ $visit->patient->year_section ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Age</label>
                    <span class="info-value">{{ $visit->patient->age ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Phone Number</label>
                    <span class="info-value">{{ $visit->patient->phone ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Address</label>
                    <span class="info-value">{{ $visit->patient->address ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Program</label>
                    <span class="info-value">{{ $visit->patient->program ?: 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Visit Info Card -->
        <div class="info-card visit-card">
            <h2 class="card-title">Visit Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Visit Date</label>
                    <span class="info-value">{{ $visit->visit_date->format('F d, Y') }}</span>
                </div>
                <div class="info-item">
                    <label>Visit Type</label>
                    <span class="info-value">{{ ucfirst(str_replace('_', ' ', $visit->visit_type ?? 'walk_in')) }}</span>
                </div>
                <div class="info-item">
                    <label>Address</label>
                    <span class="info-value">{{ $visit->address ?: 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Sex</label>
                    <span class="info-value">{{ ucfirst($visit->sex ?: 'N/A') }}</span>
                </div>
            </div>
        </div>

        <!-- Vital Signs Card -->
        @php
            use App\Support\VitalSigns;
            $assessment = $visit->getVitalSignsAssessment();
            $statuses   = $assessment['statuses'];
            $overall    = $assessment['overall'];
            $bmi        = $visit->getBMI();

            $vitalRows = [
                [
                    'label'  => 'Temperature',
                    'value'  => $visit->temperature ? $visit->temperature . ' °C' : null,
                    'status' => $statuses['temperature'],
                    'ref'    => '36.0–37.5 °C',
                ],
                [
                    'label'  => 'Pulse Rate',
                    'value'  => $visit->pulse_rate ? $visit->pulse_rate . ' bpm' : null,
                    'status' => $statuses['pulse_rate'],
                    'ref'    => '60–100 bpm',
                ],
                [
                    'label'  => 'Respiratory Rate',
                    'value'  => $visit->respiratory_rate ? $visit->respiratory_rate . ' breaths/min' : null,
                    'status' => $statuses['respiratory_rate'],
                    'ref'    => '10–20 breaths/min',
                ],
                [
                    'label'  => 'Systolic BP',
                    'value'  => $visit->bp_systolic ? $visit->bp_systolic . ' mmHg' : null,
                    'status' => $statuses['bp_systolic'],
                    'ref'    => '90–139 mmHg',
                ],
                [
                    'label'  => 'Diastolic BP',
                    'value'  => $visit->bp_diastolic ? $visit->bp_diastolic . ' mmHg' : null,
                    'status' => $statuses['bp_diastolic'],
                    'ref'    => '60–89 mmHg',
                ],
                [
                    'label'  => 'SpO₂',
                    'value'  => $visit->spo2 ? $visit->spo2 . '%' : null,
                    'status' => $statuses['spo2'],
                    'ref'    => '93–100%',
                ],
                [
                    'label'  => 'Height',
                    'value'  => $visit->height ? $visit->height . ' cm' : null,
                    'status' => null,
                    'ref'    => null,
                ],
                [
                    'label'  => 'Weight',
                    'value'  => $visit->weight ? $visit->weight . ' kg' : null,
                    'status' => null,
                    'ref'    => null,
                ],
                [
                    'label'  => 'BMI',
                    'value'  => $bmi,
                    'status' => $statuses['bmi'],
                    'ref'    => '18.5–24.9',
                ],
            ];
        @endphp

        <div class="info-card vitals-card">
            <h2 class="card-title">Vital Signs</h2>

            {{-- Overall Assessment Banner --}}
            @if ($overall)
                <div class="assessment-banner assessment-{{ $overall }}">
                    <div class="assessment-icon">
                        @if ($overall === 'abnormal') 🚨
                        @elseif ($overall === 'above_normal') ⬆️
                        @elseif ($overall === 'below_normal') ⬇️
                        @else ✅
                        @endif
                    </div>
                    <div class="assessment-text">
                        <strong>Overall Vital Signs Assessment:</strong>
                        {{ VitalSigns::label($overall) }}
                        @if ($overall === 'abnormal')
                            — One or more readings are outside safe limits. Please attend to this patient immediately.
                        @elseif ($overall !== 'normal')
                            — Some readings are outside the normal range. Monitoring recommended.
                        @else
                            — All recorded vital signs are within normal range.
                        @endif
                    </div>
                </div>
            @endif

            {{-- Per-vital detail table --}}
            <div class="vitals-detail-table">
                <table class="vs-table">
                    <thead>
                        <tr>
                            <th>Vital Sign</th>
                            <th>Recorded Value</th>
                            <th>Normal Range</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vitalRows as $row)
                            @if ($row['value'] !== null)
                                <tr class="{{ $row['status'] ? 'vs-row-' . $row['status'] : '' }}">
                                    <td class="vs-name">{{ $row['label'] }}</td>
                                    <td class="vs-val">{{ $row['value'] }}</td>
                                    <td class="vs-ref">{{ $row['ref'] ?? '—' }}</td>
                                    <td>
                                        @if ($row['status'])
                                            <span class="vs-badge vs-badge-{{ $row['status'] }}">
                                                {{ VitalSigns::icon($row['status']) }}
                                                {{ VitalSigns::label($row['status']) }}
                                            </span>
                                        @else
                                            <span class="vs-badge vs-badge-na">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Clinical Info Card -->
        <div class="info-card clinical-card">
            <h2 class="card-title">Clinical Information</h2>

            @if($visit->complaints)
                <div class="clinical-section">
                    <h3 class="section-label">Chief Complaints</h3>
                    <p class="section-content">{{ $visit->complaints }}</p>
                </div>
            @endif

            @if($visit->diagnosis)
                <div class="clinical-section">
                    <h3 class="section-label">Diagnosis</h3>
                    <p class="section-content">{{ $visit->diagnosis }}</p>
                </div>
            @endif

            @if($visit->management)
                <div class="clinical-section">
                    <h3 class="section-label">Management/Treatment</h3>
                    <p class="section-content">{{ $visit->management }}</p>
                </div>
            @endif

            @if($visit->notes)
                <div class="clinical-section">
                    <h3 class="section-label">Additional Notes</h3>
                    <p class="section-content">{{ $visit->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="page-actions">
            <a href="{{ route('clinic-visit.edit', $visit->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Record
            </a>
            <a href="{{ route('clinic-visit.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <style>
        .clinic-visit-show-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-left {
            flex: 1;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .page-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn-edit-main,
        .btn-back {
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-edit-main {
            background: #3498db;
            color: white;
        }

        .btn-edit-main:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-back {
            background: var(--bg-input);
            color: var(--text-muted);
        }

        .btn-back:hover {
            background: var(--border-input);
        }

        .info-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .card-title {
            margin: 0 0 20px 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
            border-bottom: 2px solid var(--border-inner);
            padding-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-item label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 13px;
            color: var(--text-heading);
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            width: fit-content;
        }

        .badge-student {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-faculty {
            background: #ede9fe;
            color: #6d28d9;
        }

        .badge-staff {
            background: #dcfce7;
            color: #15803d;
        }

        .vitals-card {
            border: 1px solid var(--border-card);
        }

        /* ── Assessment Banner ──────────────────────────────────── */
        .assessment-banner {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.5;
            border: 1.5px solid;
        }

        .assessment-normal     { background: rgba(39,174,96,0.10); border-color: rgba(39,174,96,0.35); color: #27ae60; }
        .assessment-above_normal { background: rgba(243,156,18,0.10); border-color: rgba(243,156,18,0.35); color: #b87e00; }
        .assessment-below_normal { background: rgba(52,152,219,0.10); border-color: rgba(52,152,219,0.35); color: #2980b9; }
        .assessment-abnormal   { background: rgba(231,76,60,0.12); border-color: rgba(231,76,60,0.5); color: #e74c3c; }

        .assessment-normal .assessment-text strong     { color: #27ae60; }
        .assessment-above_normal .assessment-text strong { color: #b87e00; }
        .assessment-below_normal .assessment-text strong { color: #2980b9; }
        .assessment-abnormal .assessment-text strong   { color: #c0392b; }

        .assessment-text { color: var(--text-heading); }
        .assessment-text strong { font-size: 13px; }

        .assessment-icon { font-size: 22px; flex-shrink: 0; line-height: 1.2; }

        /* ── Vital Signs Table ──────────────────────────────────── */
        .vitals-detail-table { overflow-x: auto; }

        .vs-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .vs-table thead tr {
            background: linear-gradient(135deg, #2980b9, #1a6ea8);
        }

        .vs-table thead th {
            padding: 10px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .vs-table tbody tr {
            border-bottom: 1px solid var(--border-inner);
            transition: background 0.15s;
        }

        .vs-table tbody tr:last-child { border-bottom: none; }
        .vs-table tbody tr:hover { background: var(--bg-input); }

        /* Row tinting by status */
        .vs-row-abnormal   { background: rgba(231,76,60,0.05) !important; }
        .vs-row-above_normal { background: rgba(243,156,18,0.04) !important; }
        .vs-row-below_normal { background: rgba(52,152,219,0.04) !important; }

        .vs-table td {
            padding: 11px 14px;
            color: var(--text-heading);
            vertical-align: middle;
        }

        .vs-name { font-weight: 600; white-space: nowrap; }

        .vs-val  { font-weight: 700; color: #3498db; }

        .vs-ref  { font-size: 11px; color: var(--text-muted); white-space: nowrap; }

        /* ── Status Badges ─────────────────────────────────────── */
        .vs-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .vs-badge-normal       { background: rgba(39,174,96,0.15); color: #27ae60; }
        .vs-badge-above_normal { background: rgba(243,156,18,0.15); color: #b87e00; }
        .vs-badge-below_normal { background: rgba(52,152,219,0.15); color: #2980b9; }
        .vs-badge-abnormal     { background: rgba(231,76,60,0.15); color: #e74c3c; font-weight: 800; }
        .vs-badge-na           { background: transparent; color: var(--text-muted); }

        .clinical-section {
            margin-bottom: 20px;
        }

        .clinical-section:last-child {
            margin-bottom: 0;
        }

        .section-label {
            margin: 0 0 8px 0;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-heading);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .section-content {
            margin: 0;
            font-size: 13px;
            color: var(--text-heading);
            line-height: 1.6;
            padding: 12px;
            background: var(--bg-input);
            border-radius: 6px;
            border-left: 4px solid #3498db;
        }

        .page-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: #27ae60;
            color: white;
        }

        .btn-primary:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--bg-input);
            color: var(--text-muted);
        }

        .btn-secondary:hover {
            background: var(--border-input);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
            }

            .header-actions {
                width: 100%;
            }

            .btn-edit-main,
            .btn-back {
                flex: 1;
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .vitals-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page-actions {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }

            .info-grid,
            .vitals-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-with-sidebar>
