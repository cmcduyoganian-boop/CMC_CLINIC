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

        <!-- Vital Signs Card -->
        <div class="info-card vitals-card">
            <h2 class="card-title">Vital Signs</h2>
            <div class="vitals-grid">
                <div class="vital-item">
                    <span class="vital-label">Temperature</span>
                    <span class="vital-value">{{ $visit->temperature ? $visit->temperature . '°C' : '-' }}</span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Pulse Rate</span>
                    <span class="vital-value">{{ $visit->pulse_rate ? $visit->pulse_rate . ' bpm' : '-' }}</span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Respiratory Rate</span>
                    <span class="vital-value">{{ $visit->respiratory_rate ? $visit->respiratory_rate . ' breaths/min' : '-' }}</span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Blood Pressure</span>
                    <span class="vital-value">{{ $visit->bp_systolic && $visit->bp_diastolic ? $visit->bp_systolic . '/' . $visit->bp_diastolic . ' mmHg' : '-' }}</span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Height</span>
                    <span class="vital-value">{{ $visit->height ? $visit->height . ' cm' : '-' }}</span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">Weight</span>
                    <span class="vital-value">{{ $visit->weight ? $visit->weight . ' kg' : '-' }}</span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">BMI</label>
                    <span class="vital-value">{{ $visit->getBMI() ? $visit->getBMI() : '-' }}</span>
                </div>
                <div class="vital-item">
                    <span class="vital-label">SpO2</label>
                    <span class="vital-value">{{ $visit->spo2 ? $visit->spo2 . '%' : '-' }}</span>
                </div>
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

        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
        }

        .vital-item {
            background: var(--bg-input);
            border-radius: 8px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .vital-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 8px;
        }

        .vital-value {
            font-size: 16px;
            font-weight: 700;
            color: #3498db;
        }

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
