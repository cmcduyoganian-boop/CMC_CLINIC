<x-app-with-sidebar>
    <x-slot name="header">Patient Details</x-slot>

    <div class="patient-detail-page">
        <!-- Header -->
        <div class="page-header">
            <div class="header-left">
                <div class="patient-header-info">
                    <div class="patient-avatar-large">
                        {{ substr($patient->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="patient-name-main">{{ $patient->name }}</h1>
                        <p class="patient-meta">
                            <span class="badge {{ $patient->getCategoryBadgeClass() }}">
                                {{ $patient->getCategoryLabel() }}
                            </span>
                            @if($patient->program)
                                <span class="meta-item">{{ $patient->program }}</span>
                            @endif
                            @if($patient->year_section)
                                <span class="meta-item">{{ $patient->year_section }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('clinic-visit.create') }}?patient={{ $patient->id }}" class="btn-new-visit">
                    <i class="fas fa-plus"></i> New Visit
                </a>
                <a href="{{ route('patients.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Patient Info Cards -->
        <div class="info-cards-grid">
            <!-- Contact Info -->
            <div class="info-card">
                <h2 class="card-title">Contact Information</h2>
                <div class="info-items">
                    @if($patient->phone)
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-phone"></i> Phone</span>
                            <span class="info-value">{{ $patient->phone }}</span>
                        </div>
                    @endif
                    @if($patient->email)
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-envelope"></i> Email</span>
                            <span class="info-value">{{ $patient->email }}</span>
                        </div>
                    @endif
                    @if($patient->address)
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-map-marker-alt"></i> Address</span>
                            <span class="info-value">{{ $patient->address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Medical Info -->
            <div class="info-card">
                <h2 class="card-title">Medical Information</h2>
                <div class="info-items">
                    @if($patient->age)
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-birthday-cake"></i> Age</span>
                            <span class="info-value">{{ $patient->age }} years old</span>
                        </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-stethoscope"></i> Total Visits</span>
                        <span class="info-value">{{ $patient->clinicVisits->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clinic Visit History -->
        <div class="history-section">
            <h2 class="section-title">
                <i class="fas fa-history"></i> Clinic Visit History
            </h2>

            @if($patient->clinicVisits->isEmpty())
                <div class="empty-history">
                    <i class="fas fa-inbox"></i>
                    <p>No clinic visits recorded yet</p>
                    <a href="{{ route('clinic-visit.create') }}?patient={{ $patient->id }}" class="btn-first-visit">
                        Record First Visit
                    </a>
                </div>
            @else
                <div class="visits-timeline">
                    @foreach($patient->clinicVisits as $visit)
                        <div class="visit-card">
                            <div class="visit-header">
                                <div class="visit-date-badge">
                                    <span class="date-day">{{ $visit->visit_date->format('d') }}</span>
                                    <span class="date-month">{{ $visit->visit_date->format('M') }}</span>
                                </div>
                                <div class="visit-info">
                                    <p class="visit-date-full">{{ $visit->visit_date->format('F d, Y') }}</p>
                                    @if($visit->diagnosis)
                                        <p class="visit-diagnosis"><strong>{{ $visit->diagnosis }}</strong></p>
                                    @endif
                                </div>
                                <a href="{{ route('clinic-visit.show', $visit->id) }}" class="btn-view-visit">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>

                            <div class="visit-details">
                                @if($visit->complaints)
                                    <div class="detail-row">
                                        <span class="label">Complaints:</span>
                                        <span class="value">{{ $visit->complaints }}</span>
                                    </div>
                                @endif

                                @if($visit->temperature || $visit->pulse_rate || $visit->respiratory_rate || $visit->bp_systolic)
                                    <div class="vitals-row">
                                        <span class="label">Vital Signs:</span>
                                        <div class="vitals-inline">
                                            @if($visit->temperature)
                                                <span class="vital">T: {{ $visit->temperature }}°C</span>
                                            @endif
                                            @if($visit->pulse_rate)
                                                <span class="vital">PR: {{ $visit->pulse_rate }} bpm</span>
                                            @endif
                                            @if($visit->respiratory_rate)
                                                <span class="vital">RR: {{ $visit->respiratory_rate }} breaths/min</span>
                                            @endif
                                            @if($visit->bp_systolic && $visit->bp_diastolic)
                                                <span class="vital">BP: {{ $visit->bp_systolic }}/{{ $visit->bp_diastolic }} mmHg</span>
                                            @endif
                                            @if($visit->height && $visit->weight)
                                                <span class="vital">BMI: {{ $visit->bmi ?? $visit->getBMI() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($visit->management)
                                    <div class="detail-row">
                                        <span class="label">Treatment:</span>
                                        <span class="value">{{ $visit->management }}</span>
                                    </div>
                                @endif

                                @if($visit->notes)
                                    <div class="detail-row">
                                        <span class="label">Notes:</span>
                                        <span class="value">{{ $visit->notes }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="visit-footer">
                                <a href="{{ route('clinic-visit.show', $visit->id) }}" class="link-view-details">
                                    View Full Details <i class="fas fa-arrow-right"></i>
                                </a>
                                <a href="{{ route('clinic-visit.edit', $visit->id) }}" class="link-edit">
                                    Edit <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        .patient-detail-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: white;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .patient-header-info {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .patient-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 32px;
            flex-shrink: 0;
        }

        .patient-name-main {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #2d3e50;
        }

        .patient-meta {
            margin: 8px 0 0 0;
            font-size: 13px;
            color: #95a5a6;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
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

        .meta-item {
            background: #f0f0f0;
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn-new-visit,
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
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-new-visit {
            background: #27ae60;
            color: white;
        }

        .btn-new-visit:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .btn-back {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-back:hover {
            background: #d4d9e0;
        }

        .info-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .card-title {
            margin: 0 0 16px 0;
            font-size: 14px;
            font-weight: 700;
            color: #2d3e50;
            border-bottom: 2px solid #e8ecf1;
            padding-bottom: 12px;
        }

        .info-items {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #95a5a6;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-value {
            color: #2d3e50;
            font-weight: 600;
        }

        .history-section {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .section-title {
            margin: 0 0 20px 0;
            font-size: 18px;
            font-weight: 700;
            color: #2d3e50;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 2px solid #e8ecf1;
            padding-bottom: 12px;
        }

        .section-title i {
            color: #3498db;
        }

        .empty-history {
            text-align: center;
            padding: 48px 24px;
            color: #95a5a6;
        }

        .empty-history i {
            font-size: 48px;
            opacity: 0.3;
            display: block;
            margin-bottom: 16px;
        }

        .empty-history p {
            margin: 0 0 16px 0;
            font-size: 14px;
        }

        .btn-first-visit {
            background: #3498db;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-first-visit:hover {
            background: #2980b9;
        }

        .visits-timeline {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .visit-card {
            border: 1px solid #e8ecf1;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.2s;
        }

        .visit-card:hover {
            border-color: #3498db;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.1);
        }

        .visit-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f9fafb;
            border-bottom: 1px solid #e8ecf1;
        }

        .visit-date-badge {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .date-day {
            font-size: 20px;
            font-weight: 700;
        }

        .date-month {
            font-size: 10px;
            font-weight: 600;
        }

        .visit-info {
            flex: 1;
        }

        .visit-date-full {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #2d3e50;
        }

        .visit-diagnosis {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #3498db;
        }

        .btn-view-visit {
            background: #3498db;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-view-visit:hover {
            background: #2980b9;
        }

        .visit-details {
            padding: 16px;
        }

        .detail-row {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 12px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .label {
            font-weight: 600;
            color: #2d3e50;
            min-width: 100px;
        }

        .value {
            color: #95a5a6;
            flex: 1;
            line-height: 1.4;
        }

        .vitals-row {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 12px;
        }

        .vitals-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            flex: 1;
        }

        .vital {
            background: #eef2f5;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            color: #3498db;
            font-weight: 600;
        }

        .visit-footer {
            display: flex;
            gap: 16px;
            padding: 12px 16px;
            background: #f9fafb;
            border-top: 1px solid #e8ecf1;
            justify-content: flex-end;
        }

        .link-view-details,
        .link-edit {
            font-size: 12px;
            font-weight: 600;
            color: #3498db;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }

        .link-view-details:hover,
        .link-edit:hover {
            color: #2980b9;
            gap: 6px;
        }

        .link-edit {
            color: #f39c12;
        }

        .link-edit:hover {
            color: #e67e22;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
            }

            .header-actions {
                width: 100%;
            }

            .btn-new-visit,
            .btn-back {
                flex: 1;
                justify-content: center;
            }

            .info-cards-grid {
                grid-template-columns: 1fr;
            }

            .visit-header {
                flex-wrap: wrap;
            }

            .visit-footer {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            .patient-avatar-large {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .patient-name-main {
                font-size: 22px;
            }

            .visit-date-badge {
                width: 50px;
                height: 50px;
            }

            .date-day {
                font-size: 16px;
            }
        }
    </style>
</x-app-with-sidebar>