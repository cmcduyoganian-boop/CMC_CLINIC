<div class="appointment-scheduler-page">
    <div class="row g-4">
        <!-- Left Column: Calendar + Upcoming Appointments -->
        <div class="col-lg-8">
            <!-- Calendar Card -->
            <div class="card calendar-card">
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
            <div class="card upcoming-card">
                <div class="card-header">
                    <h3><i class="fas fa-calendar-alt"></i> Upcoming Appointments</h3>
                </div>
                <div class="card-body">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="appointment-item">
                            <div class="appointment-date-badge">
                                <span class="month">{{ $appointment->appointment_date->format('M') }}</span>
                                <span class="day">{{ $appointment->appointment_date->format('d') }}</span>
                            </div>
                            <div class="appointment-details">
                                <h4>{{ $appointment->patient->name }}</h4>
                                <p class="appointment-time">
                                    <i class="far fa-clock"></i> {{ date('h:i A', strtotime($appointment->appointment_time)) }}
                                </p>
                                <p class="appointment-reason">{{ $appointment->reason ?? 'No reason specified' }}</p>
                            </div>
                            <div class="appointment-actions">
                                <span class="badge badge-scheduled">Scheduled</span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-appointments">
                            <i class="far fa-calendar-times"></i>
                            <p>No upcoming appointments</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Schedule Form -->
        <div class="col-lg-4">
            <div class="card schedule-card">
                <div class="card-header">
                    <h3><i class="fas fa-plus-circle"></i> Schedule New Appointment</h3>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <!-- Patient Selection -->
                        <div class="mb-3">
                            <label class="form-label">Patient Name</label>
                            <input type="text"
                                   class="form-control"
                                   wire:model="patientName"
                                   placeholder="Search patient..."
                                   @if($showScheduleForm || $patientId) readonly @endif>
                            @if($showPatientDropdown && !$patientId)
                                <div class="patient-dropdown">
                                    @foreach(\App\Models\Patient::where('name', 'like', '%' . ($this->patientName ?? '') . '%')->limit(5)->get() as $patient)
                                        <div class="patient-option" wire:click="selectPatient({{ $patient->id }})">
                                            <strong>{{ $patient->name }}</strong>
                                            <span class="text-muted">{{ $patient->category }} {{ $patient->year_section ? '- ' . $patient->year_section : '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('patientName') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category & Year Section -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" wire:model="patientCategory" @if($showScheduleForm || $patientId) disabled @endif>
                                    <option value="student">Student</option>
                                    <option value="faculty">Faculty</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Year & Section</label>
                                <input type="text" class="form-control" wire:model="patientYearSection" placeholder="e.g., 2A" @if($showScheduleForm || $patientId) readonly @endif>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" wire:model="appointmentDate" min="{{ now()->addDay()->format('Y-m-d') }}">
                                @error('appointmentDate') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Time</label>
                                <input type="time" class="form-control" wire:model="appointmentTime">
                                @error('appointmentTime') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label class="form-label">Reason for Visit</label>
                            <textarea class="form-control" wire:model="reason" rows="2" placeholder="e.g., Fever, Headache, Check-up"></textarea>
                            @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" wire:model="notes" rows="2" placeholder="Additional notes..."></textarea>
                        </div>

                        <!-- SMS Reminder Toggle -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model="smsReminder" id="smsReminder">
                                <label class="form-check-label" for="smsReminder">
                                    <strong>Send SMS Reminder</strong>
                                </label>
                            </div>
                        </div>

                        <!-- SMS Message Preview -->
                        @if($smsReminder)
                            <div class="mb-3">
                                <label class="form-label">SMS Message Preview</label>
                                <textarea class="form-control" wire:model="smsMessage" rows="3" readonly></textarea>
                                <div class="form-text">
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
        gap: 24px;
    }

    /* Calendar */
    .calendar-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .calendar-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .btn-calendar-nav {
        background: var(--bg-input);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-heading);
        transition: all 0.2s;
    }

    .btn-calendar-nav:hover {
        background: var(--border-card);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    .calendar-day-header {
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        padding: 8px;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        color: var(--text-muted);
    }

    .calendar-day.current-month {
        background: var(--bg-input);
        color: var(--text-heading);
    }

    .calendar-day.current-month:hover {
        background: #38bdf8;
        color: #fff;
    }

    .calendar-day.today {
        border: 2px solid #38bdf8;
        color: #38bdf8;
    }

    .calendar-day.has-appointment::after {
        content: '';
        position: absolute;
        bottom: 6px;
        width: 6px;
        height: 6px;
        background: #27ae60;
        border-radius: 50%;
    }

    .calendar-day.other-month {
        opacity: 0.3;
        cursor: default;
    }

    /* Upcoming Appointments */
    .upcoming-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-top: 24px;
    }

    .upcoming-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--border-inner);
        padding: 16px 24px;
    }

    .upcoming-card .card-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .upcoming-card .card-header i {
        color: #38bdf8;
        margin-right: 8px;
    }

    .upcoming-card .card-body {
        padding: 16px 24px;
    }

    .appointment-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        border-radius: 8px;
        background: var(--bg-input);
        margin-bottom: 12px;
        transition: all 0.2s;
    }

    .appointment-item:last-child {
        margin-bottom: 0;
    }

    .appointment-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .appointment-date-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #38bdf8;
        color: #fff;
        border-radius: 8px;
        padding: 8px 12px;
        min-width: 60px;
        text-align: center;
    }

    .appointment-date-badge .month {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .appointment-date-badge .day {
        font-size: 20px;
        font-weight: 700;
        line-height: 1;
    }

    .appointment-details {
        flex: 1;
        min-width: 0;
    }

    .appointment-details h4 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .appointment-time {
        margin: 0 0 4px 0;
        font-size: 12px;
        color: var(--text-muted);
    }

    .appointment-time i {
        margin-right: 4px;
    }

    .appointment-reason {
        margin: 0;
        font-size: 12px;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .appointment-actions {
        display: flex;
        align-items: center;
    }

    /* Schedule Form */
    .schedule-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .schedule-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--border-inner);
        padding: 16px 24px;
    }

    .schedule-card .card-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .schedule-card .card-header i {
        color: #27ae60;
        margin-right: 8px;
    }

    .schedule-card .card-body {
        padding: 24px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        color: var(--text-heading);
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56,189,248,0.1);
    }

    .patient-dropdown {
        position: absolute;
        z-index: 100;
        background: #fff;
        border: 1px solid var(--border-card);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        margin-top: 4px;
    }

    .patient-option {
        padding: 10px 12px;
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

    .patient-option strong {
        display: block;
        font-size: 13px;
        color: var(--text-heading);
    }

    .patient-option .text-muted {
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
    }

    .form-text {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .btn-schedule {
        background: linear-gradient(135deg, #27ae60, #1e8449);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(39,174,96,.25);
    }

    .btn-schedule:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39,174,96,.35);
    }

    .empty-appointments {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }

    .empty-appointments i {
        font-size: 48px;
        opacity: 0.3;
        display: block;
        margin-bottom: 12px;
    }

    .empty-appointments p {
        margin: 0;
        font-size: 14px;
    }

    .badge-scheduled {
        background: rgba(56,189,248,.1);
        color: #38bdf8;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .calendar-grid {
            gap: 4px;
        }

        .calendar-day {
            font-size: 12px;
        }

        .appointment-item {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
