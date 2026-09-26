<x-app-with-sidebar>
    <x-slot name="header">My Appointments</x-slot>

    <div class="my-appointments-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">My Appointments</h1>
                <p class="page-subtitle">Your scheduled clinic appointments and follow-ups</p>
            </div>
            <a href="{{ route('appointments.create') }}" class="btn-primary">
                <i class="fas fa-calendar-plus"></i> Schedule New
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="summary-grid">
            <div class="summary-card upcoming">
                <div class="summary-icon"><i class="fas fa-calendar"></i></div>
                <div>
                    <span class="summary-label">Upcoming</span>
                    <strong>{{ $patient->appointments->where('status', 'scheduled')->where('appointment_date', '>=', now()->toDateString())->count() }}</strong>
                </div>
            </div>
            <div class="summary-card completed">
                <div class="summary-icon"><i class="fas fa-check-circle"></i></div>
                <div>
                    <span class="summary-label">Completed</span>
                    <strong>{{ $patient->appointments->where('status', 'completed')->count() }}</strong>
                </div>
            </div>
            <div class="summary-card total">
                <div class="summary-icon"><i class="fas fa-list"></i></div>
                <div>
                    <span class="summary-label">Total</span>
                    <strong>{{ $patient->appointments->count() }}</strong>
                </div>
            </div>
        </div>

        <div class="appointments-panel card">
            @if($patient->appointments->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>No appointments scheduled yet.</p>
                    <a href="{{ route('appointments.create') }}" class="btn-primary small">
                        <i class="fas fa-plus"></i> Book an Appointment
                    </a>
                </div>
            @else
                <div class="appointments-list">
                    @foreach($patient->appointments as $appointment)
                        <div class="appointment-item">
                            <div class="date-box">
                                <span class="day">{{ $appointment->appointment_date ? $appointment->appointment_date->format('d') : '-' }}</span>
                                <span class="month">{{ $appointment->appointment_date ? $appointment->appointment_date->format('M') : '-' }}</span>
                            </div>

                            <div class="appointment-body">
                                <div class="appointment-topline">
                                    <strong>{{ $appointment->reason ?? 'General Checkup' }}</strong>
                                    <span class="status-badge {{ $appointment->status }}">{{ $appointment->getStatusLabel() }}</span>
                                </div>

                                <div class="appointment-meta">
                                    <span><i class="fas fa-clock"></i> {{ $appointment->appointment_time ? date('h:i A', strtotime($appointment->appointment_time)) : 'Time not set' }}</span>
                                    <span><i class="fas fa-calendar-day"></i> {{ $appointment->appointment_date ? $appointment->appointment_date->format('F d, Y') : 'Date not set' }}</span>
                                </div>

                                @if($appointment->notes)
                                    <p class="notes"><span>Notes:</span> {{ $appointment->notes }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        .my-appointments-page {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 18px 20px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
        }

        .page-title {
            margin: 0;
            font-size: clamp(24px, 2.6vw, 34px);
            font-weight: 800;
            letter-spacing: -0.04em;
            color: var(--text-heading);
        }

        .page-subtitle {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 13px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }

        .btn-primary.small {
            padding: 8px 12px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .summary-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 18px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.04);
        }

        .summary-card.upcoming .summary-icon { background: linear-gradient(135deg, #38bdf8, #2563eb); }
        .summary-card.completed .summary-icon { background: linear-gradient(135deg, #34d399, #10b981); }
        .summary-card.total .summary-icon { background: linear-gradient(135deg, #a78bfa, #7c3aed); }

        .summary-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
        }

        .summary-label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .summary-card strong {
            font-size: 24px;
            color: var(--text-heading);
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 22px;
            padding: 20px;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.06);
        }

        .empty-state {
            text-align: center;
            padding: 34px 18px;
            color: var(--text-muted);
        }

        .empty-state i {
            display: block;
            font-size: 36px;
            margin-bottom: 12px;
            opacity: 0.38;
        }

        .empty-state p {
            margin: 0 0 16px;
            font-size: 14px;
        }

        .appointments-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .appointment-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px;
            background: var(--bg-input);
            border: 1px solid var(--border-inner);
            border-radius: 16px;
        }

        .date-box {
            width: 62px;
            min-width: 62px;
            height: 62px;
            border-radius: 14px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 18px rgba(37, 99, 235, 0.18);
        }

        .date-box .day {
            font-size: 24px;
            font-weight: 800;
            line-height: 1;
        }

        .date-box .month {
            font-size: 10px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            opacity: 0.9;
        }

        .appointment-body {
            flex: 1;
            min-width: 0;
        }

        .appointment-topline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .appointment-topline strong {
            font-size: 15px;
            color: var(--text-heading);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .status-badge.scheduled {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-badge.completed {
            background: #dcfce7;
            color: #15803d;
        }

        .status-badge.no-show {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-badge.cancelled {
            background: #f3f4f6;
            color: #4b5563;
        }

        .appointment-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 18px;
            color: var(--text-muted);
            font-size: 12px;
        }

        .appointment-meta i {
            margin-right: 6px;
            font-size: 11px;
        }

        .notes {
            margin: 10px 0 0;
            color: var(--text-heading);
            font-size: 12px;
            line-height: 1.5;
        }

        .notes span {
            font-weight: 700;
            color: var(--text-muted);
        }

        @media (max-width: 640px) {
            .page-header {
                align-items: flex-start;
            }

            .appointment-item {
                flex-direction: column;
            }

            .date-box {
                width: 52px;
                min-width: 52px;
                height: 52px;
            }
        }
    </style>
</x-app-with-sidebar>
