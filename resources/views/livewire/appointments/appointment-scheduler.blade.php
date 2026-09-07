<div class="appointment-scheduler-page">
    <div class="row g-4">
        <!-- Left Column: Calendar + Upcoming Appointments -->
        <div class="col-lg-8">
            <!-- Calendar Card -->
            <div class="calendar-card">
                <div class="calendar-header">
                    <button type="button" class="btn-calendar-nav" wire:click="previousMonth">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h3 class="calendar-title">{{ \Carbon\Carbon::create($this->currentYear, $this->currentMonth, 1)->format('F Y') }}</h3>
                    <button type="button" class="btn-calendar-nav" wire:click="nextMonth">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="calendar-grid">
                    <div class="calendar-day-header">Sun</div>
                    <div class="calendar-day-header">Mon</div>
                    <div class="calendar-day-header">Tue</div>
                    <div class="calendar-day-header">Wed</div>
                    <div class="calendar-day-header">Thu</div>
                    <div class="calendar-day-header">Fri</div>
                    <div class="calendar-day-header">Sat</div>

                    @foreach($calendarDays as $day)
                        @if($day['isCurrentMonth'])
                            <div class="calendar-day current-month {{ $day['isToday'] ? 'today' : '' }} {{ $day['hasAppointment'] ? 'has-appointment' : '' }}"
                                 wire:click="selectDate({{ $day['day'] }})">
                                {{ $day['day'] }}
                                @if($day['hasAppointment'])
                                    <span class="appointment-dot"></span>
                                @endif
                            </div>
                        @else
                            <div class="calendar-day other-month">{{ $day['day'] }}</div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="upcoming-card">
                <div class="upcoming-header">
                    <h3><i class="fas fa-calendar-check"></i> Upcoming Appointments</h3>
                    <span class="upcoming-badge">{{ $upcomingAppointments->count() }}</span>
                </div>
                <div class="upcoming-body">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="appointment-item">
                            <div class="appointment-avatar">
                                {{ substr($appointment->patient->name, 0, 2) }}
                            </div>
                            <div class="appointment-details">
                                <h4>{{ $appointment->patient->name }}</h4>
                                <p class="appointment-meta">
                                    <span class="meta-item">
                                        <i class="far fa-calendar"></i> {{ $appointment->appointment_date->format('M d, Y') }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="far fa-clock"></i> {{ date('h:i A', strtotime($appointment->appointment_time)) }}
                                    </span>
                                </p>
                                <p class="appointment-reason">{{ $appointment->reason ?? 'No reason specified' }}</p>
                            </div>
                            <div class="appointment-status">
                                <span class="status-indicator scheduled"></span>
                                <span class="status-text">Scheduled</span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-appointments">
                            <div class="empty-icon">
                                <i class="far fa-calendar-times"></i>
                            </div>
                            <p>No upcoming appointments</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Schedule Form -->
        <div class="col-lg-4">
            <div class="schedule-card">
                <div class="schedule-header">
                    <div class="schedule-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h3>Schedule New Appointment</h3>
                </div>
                <div class="schedule-body">
                    <form wire:submit.prevent="save">
                        <!-- Patient Selection -->
                        <div class="form-group">
                            <label class="form-label">Patient Name</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text"
                                       class="form-control"
                                       wire:model="patientName"
                                       placeholder="Search patient..."
                                       @if($showScheduleForm || $patientId) readonly @endif>
                            </div>
                            @if($showPatientDropdown && !$patientId)
                                <div class="patient-dropdown">
                                    @foreach(\App\Models\Patient::where('name', 'like', '%' . ($this->patientName ?? '') . '%')->limit(5)->get() as $patient)
                                        <div class="patient-option" wire:click="selectPatient({{ $patient->id }})">
                                            <div class="patient-option-avatar">
                                                {{ substr($patient->name, 0, 2) }}
                                            </div>
                                            <div class="patient-option-info">
                                                <strong>{{ $patient->name }}</strong>
                                                <span>{{ $patient->category }} {{ $patient->year_section ? '- ' . $patient->year_section : '' }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('patientName') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category & Year Section -->
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Category</label>
                                    <div class="input-wrapper">
                                        <select class="form-select" wire:model="patientCategory" @if($showScheduleForm || $patientId) disabled @endif>
                                            <option value="student">Student</option>
                                            <option value="faculty">Faculty</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Year & Section</label>
                                    <div class="input-wrapper">
                                        <input type="text" class="form-control" wire:model="patientYearSection" placeholder="e.g., 2A" @if($showScheduleForm || $patientId) readonly @endif>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Date</label>
                                    <div class="input-wrapper">
                                        <i class="far fa-calendar input-icon"></i>
                                        <input type="date" class="form-control" wire:model="appointmentDate" min="{{ now()->addDay()->format('Y-m-d') }}">
                                    </div>
                                    @error('appointmentDate') <span class="error-text">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">Time</label>
                                    <div class="input-wrapper">
                                        <i class="far fa-clock input-icon"></i>
                                        <input type="time" class="form-control" wire:model="appointmentTime">
                                    </div>
                                    @error('appointmentTime') <span class="error-text">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="form-group mb-3">
                            <label class="form-label">Reason for Visit</label>
                            <div class="input-wrapper">
                                <textarea class="form-control" wire:model="reason" rows="2" placeholder="e.g., Fever, Headache, Check-up"></textarea>
                            </div>
                            @error('reason') <span class="error-text">{{ $message }}</span> @enderror
                        </div>

                        <!-- Notes -->
                        <div class="form-group mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <div class="input-wrapper">
                                <textarea class="form-control" wire:model="notes" rows="2" placeholder="Additional notes..."></textarea>
                            </div>
                        </div>

                        <!-- SMS Reminder Toggle -->
                        <div class="form-group mb-3">
                            <div class="sms-toggle-wrapper">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="smsReminder" id="smsReminder">
                                    <label class="form-check-label" for="smsReminder">
                                        <i class="fas fa-sms"></i> Send SMS Reminder
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- SMS Message Preview -->
                        @if($smsReminder)
                            <div class="sms-preview">
                                <label class="form-label">SMS Message Preview</label>
                                <div class="sms-preview-box">
                                    <textarea class="form-control" wire:model="smsMessage" rows="3" readonly></textarea>
                                </div>
                                <div class="sms-info">
                                    <i class="fas fa-info-circle"></i> This message will be sent as an SMS reminder.
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-schedule w-100">
                            <i class="fas fa-calendar-check"></i> Schedule Appointment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .appointment-scheduler-page {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    /* ===== Calendar ===== */
    .calendar-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .calendar-card:hover {
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 12px 32px rgba(0,0,0,0.08);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-inner);
    }

    .calendar-title {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.2px;
    }

    .btn-calendar-nav {
        background: var(--bg-input);
        border: 1px solid var(--border-card);
        border-radius: 10px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-heading);
        transition: all 0.2s;
        font-size: 14px;
    }

    .btn-calendar-nav:hover {
        background: #38bdf8;
        color: #fff;
        border-color: #38bdf8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(56,189,248,0.3);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }

    .calendar-day-header {
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 10px;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        color: var(--text-muted);
        border: 1px solid transparent;
    }

    .calendar-day.current-month {
        background: var(--bg-input);
        color: var(--text-heading);
    }

    .calendar-day.current-month:hover {
        background: #38bdf8;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(56,189,248,0.3);
    }

    .calendar-day.today {
        border: 2px solid #38bdf8;
        color: #38bdf8;
        background: rgba(56,189,248,0.08);
    }

    .calendar-day.has-appointment::after {
        content: '';
        position: absolute;
        bottom: 6px;
        width: 7px;
        height: 7px;
        background: #27ae60;
        border-radius: 50%;
        box-shadow: 0 0 0 2px rgba(39,174,96,0.2);
    }

    .calendar-day.other-month {
        opacity: 0.25;
        cursor: default;
    }

    /* ===== Upcoming Appointments ===== */
    .upcoming-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06);
        margin-top: 24px;
        overflow: hidden;
    }

    .upcoming-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 28px;
        border-bottom: 1px solid var(--border-inner);
    }

    .upcoming-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .upcoming-header h3 i {
        color: #38bdf8;
        font-size: 20px;
    }

    .upcoming-badge {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: #fff;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(56,189,248,0.3);
    }

    .upcoming-body {
        padding: 20px 28px;
    }

    .appointment-item {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 18px;
        border-radius: 12px;
        background: var(--bg-input);
        margin-bottom: 14px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .appointment-item:last-child {
        margin-bottom: 0;
    }

    .appointment-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        border-color: var(--border-card);
    }

    .appointment-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(56,189,248,0.3);
    }

    .appointment-details {
        flex: 1;
        min-width: 0;
    }

    .appointment-details h4 {
        margin: 0 0 6px 0;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .appointment-meta {
        margin: 0 0 6px 0;
        font-size: 12px;
        color: var(--text-muted);
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .meta-item i {
        font-size: 11px;
        color: #38bdf8;
    }

    .appointment-reason {
        margin: 0;
        font-size: 12px;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .appointment-status {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #27ae60;
        box-shadow: 0 0 0 3px rgba(39,174,96,0.15);
    }

    .status-indicator.scheduled {
        background: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56,189,248,0.15);
    }

    .status-text {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ===== Schedule Form ===== */
    .schedule-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .schedule-header {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: #fff;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .schedule-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        backdrop-filter: blur(10px);
    }

    .schedule-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .schedule-body {
        padding: 28px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 14px;
        font-family: 'Figtree', sans-serif;
        color: var(--text-heading);
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #38bdf8;
        box-shadow: 0 0 0 4px rgba(56,189,248,0.1);
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 14px;
        pointer-events: none;
        z-index: 1;
    }

    .input-wrapper .form-control,
    .input-wrapper .form-select {
        padding-left: 40px;
    }

    .input-wrapper textarea {
        padding-left: 40px;
    }

    .error-text {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: #e74c3c;
        font-weight: 500;
    }

    .patient-dropdown {
        position: absolute;
        z-index: 1000;
        background: #fff;
        border: 1px solid var(--border-card);
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        width: 100%;
        max-height: 220px;
        overflow-y: auto;
        margin-top: 6px;
    }

    .patient-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid var(--border-inner);
        transition: background 0.15s;
    }

    .patient-option:last-child {
        border-bottom: none;
    }

    .patient-option:hover {
        background: var(--bg-input);
    }

    .patient-option-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }

    .patient-option-info {
        flex: 1;
        min-width: 0;
    }

    .patient-option-info strong {
        display: block;
        font-size: 13px;
        color: var(--text-heading);
        font-weight: 600;
    }

    .patient-option-info span {
        font-size: 11px;
        color: var(--text-muted);
    }

    .form-check-input {
        width: 2.5em;
        height: 1.4em;
        cursor: pointer;
    }

    .form-check-label {
        cursor: pointer;
        user-select: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: var(--text-heading);
    }

    .sms-toggle-wrapper {
        background: var(--bg-input);
        border-radius: 10px;
        padding: 14px;
        border: 1px solid var(--border-input);
    }

    .sms-preview {
        margin-bottom: 18px;
    }

    .sms-preview-box {
        background: #f8fafc;
        border: 1px solid var(--border-card);
        border-radius: 10px;
        padding: 12px;
    }

    .sms-info {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-schedule {
        background: linear-gradient(135deg, #27ae60, #1e8449);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(39,174,96,0.3);
        width: 100%;
    }

    .btn-schedule:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(39,174,96,0.4);
    }

    .empty-appointments {
        text-align: center;
        padding: 48px 24px;
        color: var(--text-muted);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--bg-input);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .empty-icon i {
        font-size: 36px;
        opacity: 0.4;
    }

    .empty-appointments p {
        margin: 0;
        font-size: 14px;
        font-weight: 500;
    }

    .badge-scheduled {
        background: rgba(56,189,248,.1);
        color: #38bdf8;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .calendar-grid {
            gap: 6px;
        }

        .calendar-day {
            font-size: 12px;
        }

        .appointment-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .calendar-header {
            padding-bottom: 12px;
        }

        .calendar-title {
            font-size: 16px;
        }
    }
</style>
