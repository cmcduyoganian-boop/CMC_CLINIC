<x-app-with-sidebar>
    <x-slot name="header">Appointment Details</x-slot>

    <div class="appointment-details-page">
        <div class="details-card">
            <div class="details-header">
                <div>
                    <h1 class="patient-name">{{ $appointment->patient->name ?? 'Unknown Patient' }}</h1>
                    <span class="badge {{ $appointment->patient->getCategoryBadgeClass() }}">
                        {{ $appointment->patient->getCategoryLabel() }}
                    </span>
                </div>
                <span class="status-badge {{ $appointment->getStatusBadgeClass() }}">
                    {{ $appointment->getStatusLabel() }}
                </span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label>Date</label>
                    <span class="info-value">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                </div>
                <div class="info-item">
                    <label>Time</label>
                    <span class="info-value">{{ date('h:i A', strtotime($appointment->appointment_time)) }}</span>
                </div>
                <div class="info-item">
                    <label>Year &amp; Section</label>
                    <span class="info-value">{{ $appointment->patient->year_section ?: '-' }}</span>
                </div>
            </div>

            <div class="info-item full-width">
                <label>Reason for Appointment</label>
                <span class="info-value">{{ $appointment->reason ?: 'No reason provided' }}</span>
            </div>

            <div class="info-item full-width">
                <label>Additional Notes</label>
                <span class="info-value">{{ $appointment->notes ?: 'No notes' }}</span>
            </div>

            <div class="details-actions">
                <a href="{{ route('appointments.index') }}" class="btn btn-cancel">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <style>
        .appointment-details-page {
            display: flex;
            justify-content: center;
            padding: 24px;
        }

        .details-card {
            background: white;
            border-radius: 10px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            max-width: 600px;
            width: 100%;
        }

        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e8ecf1;
        }

        .patient-name {
            margin: 0 0 8px 0;
            font-size: 22px;
            font-weight: 700;
            color: #2d3e50;
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

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .badge-scheduled {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-completed {
            background: #d5f4e6;
            color: #27ae60;
        }

        .badge-no-show {
            background: #ffeaa7;
            color: #d68910;
        }

        .badge-cancelled {
            background: #fadbd8;
            color: #c0392b;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 16px;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-item label {
            font-size: 11px;
            font-weight: 700;
            color: #95a5a6;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 14px;
            color: #2d3e50;
        }

        .details-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e8ecf1;
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
            font-family: 'Figtree', sans-serif;
            text-decoration: none;
        }

        .btn-cancel {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-cancel:hover {
            background: #d4d9e0;
        }

        .btn-edit {
            background: #3498db;
            color: white;
        }

        .btn-edit:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .details-card {
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .details-header {
                flex-direction: column;
                gap: 12px;
            }

            .details-actions {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</x-app-with-sidebar>