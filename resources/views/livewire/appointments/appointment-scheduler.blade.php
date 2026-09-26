<div class="asch-page">

    {{-- ── Page Title Bar ── --}}
    <div class="asch-title-bar">
        <div>
            <h1 class="asch-title"><i class="fas fa-calendar-alt"></i> Appointment Scheduler</h1>
            <p class="asch-subtitle">Manage and schedule patient visits efficiently</p>
        </div>
        <button type="button" class="asch-btn-new" onclick="document.getElementById('patientNameInput').focus()">
            <i class="fas fa-calendar-plus"></i> Schedule Appointment
        </button>
    </div>

    {{-- ── Two-Column Layout ── --}}
    <div class="asch-columns">

        {{-- Left: Calendar + Upcoming --}}
        <div class="asch-left">

            {{-- Calendar --}}
            <div class="asch-card">
                <div class="cal-header">
                    <button type="button" class="cal-nav" wire:click="previousMonth">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h3 class="cal-month">{{ \Carbon\Carbon::create($this->currentYear, $this->currentMonth, 1)->format('F Y') }}</h3>
                    <button type="button" class="cal-nav" wire:click="nextMonth">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="cal-grid">
                    @foreach(['SU','MO','TU','WE','TH','FR','SA'] as $d)
                        <div class="cal-dow">{{ $d }}</div>
                    @endforeach

                    @foreach($calendarDays as $day)
                        @if($day['isCurrentMonth'])
                            <div class="cal-day {{ $day['isToday'] ? 'is-today' : '' }} {{ $day['hasAppointment'] ? 'has-appt' : '' }}"
                                 wire:click="selectDate({{ $day['day'] }})">
                                {{ $day['day'] }}
                                @if($day['hasAppointment'])
                                    <span class="appt-dot"></span>
                                @endif
                            </div>
                        @else
                            <div class="cal-day is-other">{{ $day['day'] }}</div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Upcoming Appointments --}}
            <div class="asch-card" style="margin-top:16px;">
                <div class="upcoming-hd">
                    <span class="upcoming-hd-title">
                        <i class="fas fa-clock"></i> Upcoming Appointments
                    </span>
                    <span class="upcoming-count">{{ $upcomingAppointments->count() }} SCHEDULED</span>
                </div>

                <div class="upcoming-list">
                    @forelse($upcomingAppointments as $appt)
                        @php
                            $isToday = $appt->appointment_date->isToday();
                            $isTomorrow = $appt->appointment_date->isTomorrow();
                        @endphp
                        <div class="upc-item" wire:click="viewAppointment({{ $appt->id }})" style="cursor: pointer;">
                            <div class="upc-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="upc-info">
                                <p class="upc-name">{{ $appt->patient->name }}</p>
                                <p class="upc-time">
                                    <i class="far fa-clock"></i>
                                    @if($isToday) Today @elseif($isTomorrow) Tomorrow @else {{ $appt->appointment_date->format('M d') }} @endif
                                    {{ date('g:i A', strtotime($appt->appointment_time)) }}
                                </p>
                                @if($appt->reason)
                                <p class="upc-reason">
                                    <i class="fas fa-stethoscope"></i>
                                    {{ $appt->reason }}
                                </p>
                                @endif
                            </div>
                            <span class="upc-badge {{ $isToday ? 'badge-today' : 'badge-upcoming' }}">
                                {{ $isToday ? 'Today' : 'Upcoming' }}
                            </span>
                        </div>
                    @empty
                        <div class="upc-empty">
                            <i class="far fa-calendar-times"></i>
                            <p>No upcoming appointments</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right: Schedule Form --}}
        <div class="asch-right">
            <div class="asch-card">
                <h3 class="form-heading">Schedule New Appointment</h3>
                <p class="form-subheading">Fill in the clinical appointment details below</p>

                <form wire:submit.prevent="save" style="margin-top:20px;">

                    {{-- Patient --}}
                    <div class="fg">
                        <label class="fg-label">Patient</label>
                        <div class="fg-input-wrap">
                            <input id="patientNameInput"
                                   type="text"
                                   class="fg-input"
                                   wire:model="patientName"
                                   placeholder="Search or type patient name..."
                                   @if($showScheduleForm || $patientId) readonly @endif>
                            @if($patientId)
                                <i class="fas fa-check fg-check"></i>
                            @endif
                        </div>
                        @if($showPatientDropdown && !$patientId)
                            <div class="pat-dropdown">
                                @foreach(\App\Models\Patient::where('name', 'like', '%' . ($this->patientName ?? '') . '%')->limit(5)->get() as $patient)
                                    <div class="pat-option" wire:click="selectPatient({{ $patient->id }})">
                                        <div class="pat-opt-avatar">{{ strtoupper(substr($patient->name, 0, 2)) }}</div>
                                        <div>
                                            <strong>{{ $patient->name }}</strong>
                                            <span>{{ ucfirst($patient->category) }}{{ $patient->year_section ? ' · ' . $patient->year_section : '' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @error('patientName') <span class="fg-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Category + Year --}}
                    <div class="fg-row">
                        <div class="fg">
                            <label class="fg-label">Category</label>
                            <select class="fg-input" wire:model="patientCategory" @if($showScheduleForm || $patientId) disabled @endif>
                                <option value="student">Student</option>
                                <option value="faculty">Faculty</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div class="fg">
                            <label class="fg-label">Year & Section</label>
                            <input type="text" class="fg-input" wire:model="patientYearSection" placeholder="e.g. 2A" @if($showScheduleForm || $patientId) readonly @endif>
                        </div>
                    </div>

                    {{-- Date + Time --}}
                    <div class="fg-row">
                        <div class="fg">
                            <label class="fg-label">Date</label>
                            <input type="date" class="fg-input" wire:model="appointmentDate" min="{{ now()->addDay()->format('Y-m-d') }}">
                            @error('appointmentDate') <span class="fg-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="fg">
                            <label class="fg-label">Time</label>
                            <input type="time" class="fg-input" wire:model="appointmentTime">
                            @error('appointmentTime') <span class="fg-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Reason --}}
                    <div class="fg">
                        <label class="fg-label">Reason for Visit</label>
                        <textarea class="fg-input fg-textarea" wire:model="reason" rows="2" placeholder="e.g., Fever, Headache, Check-up"></textarea>
                        @error('reason') <span class="fg-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="fg">
                        <label class="fg-label">Notes <span style="font-weight:400;opacity:.6;">(Optional)</span></label>
                        <textarea class="fg-input fg-textarea" wire:model="notes" rows="2" placeholder="Additional notes..."></textarea>
                    </div>

                    {{-- SMS Toggle --}}
                    <div class="sms-row">
                        <label class="sms-toggle-label" for="smsReminder">
                            <input class="sms-checkbox" type="checkbox" wire:model="smsReminder" id="smsReminder">
                            <span class="sms-toggle-track"><span class="sms-toggle-thumb"></span></span>
                            <i class="fas fa-comment-dots" style="color:#2980b9;"></i>
                            Send SMS reminder
                        </label>
                    </div>

                    @if($smsReminder)
                        <div class="sms-preview-block">
                            <p class="sms-preview-label">MESSAGE PREVIEW</p>
                            <div class="sms-preview-bubble">{{ $smsMessage }}</div>
                        </div>
                    @endif

                    {{-- Patient Appointment History --}}
                    @if($patientId && $patientHistory->count() > 0)
                        <div class="patient-history-block">
                            <p class="ph-label"><i class="fas fa-history"></i> Appointment History for {{ $patientName }} ({{ $patientHistory->count() }} visits)</p>
                            <div class="ph-list">
                                @foreach($patientHistory as $appt)
                                    <div class="ph-item">
                                        <div class="ph-date">
                                            <span class="ph-date-main">{{ $appt->appointment_date->format('M d, Y') }}</span>
                                            <span class="ph-date-time">{{ date('g:i A', strtotime($appt->appointment_time)) }}</span>
                                        </div>
                                        <div class="ph-details">
                                            @if($appt->reason)
                                                <span class="ph-reason">{{ $appt->reason }}</span>
                                            @endif
                                            <span class="ph-status status-{{ $appt->status }}">
                                                {{ ucfirst($appt->status) }}
                                            </span>
                                        </div>
                                        <button type="button" class="ph-delete" wire:click="deleteAppointment({{ $appt->id }})" wire:confirm="Delete this appointment for {{ $appt->patient->name }} on {{ $appt->appointment_date->format('M d, Y') }}?">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Submit --}}
                    <button type="submit" class="btn-schedule">
                        <i class="fas fa-calendar-check"></i> SCHEDULE
                    </button>
                    <p class="schedule-hint">Appointment confirmation will be sent to the patient immediately.</p>
                </form>
            </div>
        </div>
    </div>

    {{-- Appointment Detail Modal — INSIDE root div so Livewire controls it --}}
    @if($showAppointmentDetail && $selectedAppointment)
<div class="appt-modal-overlay" wire:click="closeAppointmentDetail">
    <div class="appt-modal" wire:click="$event.stopPropagation()">
        <div class="appt-modal-header">
            <h3 class="appt-modal-title">Appointment Details</h3>
            <button type="button" class="appt-modal-close" wire:click="closeAppointmentDetail">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="appt-modal-body">
            <div class="appt-detail-row">
                <span class="appt-detail-label">Patient</span>
                <span class="appt-detail-value"><i class="fas fa-user"></i> {{ $selectedAppointment->patient->name }}</span>
            </div>
            <div class="appt-detail-row">
                <span class="appt-detail-label">Category</span>
                <span class="appt-detail-value"><i class="fas fa-tag"></i> {{ ucfirst($selectedAppointment->patient->category) }}</span>
            </div>
            <div class="appt-detail-row">
                <span class="appt-detail-label">Date</span>
                <span class="appt-detail-value"><i class="far fa-calendar-alt"></i> {{ $selectedAppointment->appointment_date->format('F j, Y') }}</span>
            </div>
            <div class="appt-detail-row">
                <span class="appt-detail-label">Time</span>
                <span class="appt-detail-value"><i class="far fa-clock"></i> {{ date('g:i A', strtotime($selectedAppointment->appointment_time)) }}</span>
            </div>
            <div class="appt-detail-row">
                <span class="appt-detail-label">Status</span>
                <span class="appt-detail-value">
                    <span class="badge {{ $selectedAppointment->statusBadgeClass }}">{{ $selectedAppointment->statusLabel }}</span>
                </span>
            </div>
            @if($selectedAppointment->reason)
            <div class="appt-reason-box">
                <p class="appt-reason-label"><i class="fas fa-stethoscope"></i> Reason for Visit</p>
                <p class="appt-reason-text">{{ $selectedAppointment->reason }}</p>
            </div>
            @endif
            @if($selectedAppointment->notes)
            <div class="appt-reason-box" style="margin-top:12px;">
                <p class="appt-reason-label"><i class="fas fa-sticky-note"></i> Notes</p>
                <p class="appt-reason-text">{{ $selectedAppointment->notes }}</p>
            </div>
            @endif
        </div>
        @if($selectedAppointment->status === 'scheduled')
        <div class="appt-modal-footer">
            <p class="appt-footer-hint"><i class="fas fa-info-circle"></i> Update appointment status:</p>
            <div class="appt-footer-actions">
                <button type="button" class="btn-appt-action btn-check" wire:click="markCompleted">
                    <i class="fas fa-check-circle"></i>
                    <span>
                        <strong>Completed</strong>
                        <small>Patient came in</small>
                    </span>
                </button>
                <button type="button" class="btn-appt-action btn-no-show" wire:click="markNoShow">
                    <i class="fas fa-user-slash"></i>
                    <span>
                        <strong>No-Show</strong>
                        <small>Didn't arrive</small>
                    </span>
                </button>
                <button type="button" class="btn-appt-action btn-cancel-appt" wire:click="markCancelled"
                    wire:confirm="Cancel this appointment for {{ $selectedAppointment->patient->name }}?">
                    <i class="fas fa-ban"></i>
                    <span>
                        <strong>Cancel</strong>
                        <small>Admin cancel</small>
                    </span>
                </button>
            </div>
        </div>
        @else
        <div class="appt-modal-footer appt-footer-readonly">
            <span class="badge {{ $selectedAppointment->getStatusBadgeClass() }}" style="font-size:13px;padding:6px 14px;">
                {{ $selectedAppointment->getStatusLabel() }}
            </span>
            <span style="font-size:12px;color:var(--text-muted);">This appointment has already been resolved.</span>
        </div>
        @endif
    </div>
</div>
    @endif

<style>
/* ── Page layout ────────────────────────────────────────────────── */
.asch-page { display: flex; flex-direction: column; gap: 0; }

.asch-title-bar {
    display: flex; align-items: flex-start;
    justify-content: space-between; flex-wrap: wrap; gap: 12px;
    margin-bottom: 22px;
}
.asch-title {
    margin: 0; font-size: 22px; font-weight: 800;
    color: var(--text-heading);
    display: flex; align-items: center; gap: 10px;
}
.asch-title i { color: #2980b9; font-size: 20px; }
.asch-subtitle { margin: 4px 0 0; font-size: 13px; color: var(--text-muted); }

.asch-btn-new {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #2980b9, #1a6ea8);
    color: #fff; border: none; border-radius: 10px;
    padding: 11px 20px; font-size: 13px; font-weight: 700;
    cursor: pointer; font-family: inherit;
    box-shadow: 0 4px 14px rgba(41,128,185,0.35);
    transition: all 0.2s;
}
.asch-btn-new:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(41,128,185,0.4); }

/* ── Columns ─────────────────────────────────────────────────────── */
.asch-columns {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 20px;
    align-items: start;
}
.asch-left  { display: flex; flex-direction: column; gap: 0; }
.asch-right { position: sticky; top: 80px; }

/* ── Card ────────────────────────────────────────────────────────── */
.asch-card {
    background: var(--bg-card);
    border: 1px solid var(--border-card);
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
}

/* ── Calendar ────────────────────────────────────────────────────── */
.cal-header {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid var(--border-inner);
}
.cal-month { margin: 0; font-size: 18px; font-weight: 800; color: var(--text-heading); }

.cal-nav {
    width: 34px; height: 34px; border-radius: 8px;
    background: var(--bg-input); border: 1px solid var(--border-card);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text-muted); font-size: 12px;
    transition: all 0.18s; font-family: inherit;
}
.cal-nav:hover {
    background: linear-gradient(135deg, #2980b9, #1a6ea8);
    color: #fff; border-color: transparent;
    box-shadow: 0 4px 12px rgba(41,128,185,0.3);
}

.cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
}
.cal-dow {
    text-align: center; font-size: 10px; font-weight: 700;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: .8px; padding: 8px 0;
}

.cal-day {
    aspect-ratio: 1;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    border-radius: 10px; font-size: 13px; font-weight: 600;
    cursor: pointer; position: relative;
    color: var(--text-muted); border: 1px solid transparent;
    transition: all 0.18s;
}

/* Current month days */
.cal-day:not(.is-other):not(.is-today) {
    background: var(--bg-input);
    color: var(--text-heading);
    border-color: var(--border-inner);
}
.cal-day:not(.is-other):not(.is-today):hover {
    background: rgba(41,128,185,0.12);
    color: #2980b9;
    border-color: rgba(41,128,185,0.35);
    transform: translateY(-1px);
}

/* Today */
.cal-day.is-today {
    background: linear-gradient(135deg, #2980b9, #1a6ea8) !important;
    color: #fff !important; border-color: transparent !important;
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(41,128,185,0.45);
}
.cal-day.is-today:hover { box-shadow: 0 6px 20px rgba(41,128,185,0.55); transform: translateY(-1px); }

/* Appointment dot */
.appt-dot {
    position: absolute; bottom: 4px;
    width: 5px; height: 5px; border-radius: 50%;
    background: #27ae60;
    box-shadow: 0 0 0 2px rgba(39,174,96,0.25);
}
.is-today .appt-dot { background: rgba(255,255,255,0.85); box-shadow: none; }

/* Other month */
.cal-day.is-other { opacity: 0.18; cursor: default; }

/* ── Upcoming List ────────────────────────────────────────────────── */
.upcoming-hd {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-inner);
}
.upcoming-hd-title {
    font-size: 14px; font-weight: 700; color: var(--text-heading);
    display: flex; align-items: center; gap: 8px;
}
.upcoming-hd-title i { color: #2980b9; }
.upcoming-count {
    font-size: 10px; font-weight: 800; letter-spacing: .6px;
    color: #2980b9;
    background: rgba(41,128,185,0.1);
    padding: 3px 10px; border-radius: 20px;
    border: 1px solid rgba(41,128,185,0.2);
}

.upcoming-list { display: flex; flex-direction: column; gap: 8px; max-height: 320px; overflow-y: auto; }
.upcoming-list::-webkit-scrollbar { width: 3px; }
.upcoming-list::-webkit-scrollbar-thumb { background: var(--border-card); border-radius: 2px; }

.upc-item {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 13px;
    background: var(--bg-input);
    border: 1px solid var(--border-inner);
    border-radius: 10px;
    transition: all 0.18s;
}
.upc-item:hover {
    border-color: rgba(41,128,185,0.35);
    transform: translateX(3px);
    box-shadow: 0 2px 10px rgba(41,128,185,0.1);
}

.upc-avatar {
    font-size: 26px; color: var(--text-muted); flex-shrink: 0;
    width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(41,128,185,0.08);
    border-radius: 50%;
    color: #2980b9;
}

.upc-info { flex: 1; min-width: 0; }
.upc-name { margin: 0; font-size: 13px; font-weight: 700; color: var(--text-heading); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.upc-time { margin: 2px 0 0; font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 5px; }
.upc-time i { color: #2980b9; font-size: 10px; }
.upc-reason { margin: 4px 0 0; font-size: 11px; color: var(--text-heading); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }
.upc-reason i { color: #27ae60; font-size: 9px; margin-right: 4px; }

.upc-badge {
    font-size: 10px; font-weight: 800; padding: 3px 10px;
    border-radius: 20px; white-space: nowrap; flex-shrink: 0;
}
.badge-today    { background: rgba(39,174,96,0.12); color: #27ae60; border: 1px solid rgba(39,174,96,0.25); }
.badge-upcoming { background: rgba(41,128,185,0.12); color: #2980b9; border: 1px solid rgba(41,128,185,0.2); }

/* Status badges */
.badge-scheduled  { background: rgba(41,128,185,0.12); color: #2980b9; border: 1px solid rgba(41,128,185,0.2); }
.badge-completed  { background: rgba(39,174,96,0.12); color: #27ae60; border: 1px solid rgba(39,174,96,0.25); }
.badge-no-show    { background: rgba(231,76,60,0.12); color: #e74c3c; border: 1px solid rgba(231,76,60,0.25); }
.badge-cancelled  { background: rgba(149,165,166,0.12); color: #95a5a6; border: 1px solid rgba(149,165,166,0.25); }
.badge-gray       { background: rgba(149,165,166,0.12); color: #95a5a6; border: 1px solid rgba(149,165,166,0.25); }

.upc-empty {
    text-align: center; padding: 36px 20px;
    color: var(--text-muted);
}
.upc-empty i { font-size: 28px; display: block; margin-bottom: 10px; opacity: 0.3; }
.upc-empty p  { margin: 0; font-size: 13px; }

/* ── Schedule Form ────────────────────────────────────────────────── */
.form-heading    { margin: 0; font-size: 16px; font-weight: 800; color: var(--text-heading); }
.form-subheading { margin: 4px 0 0; font-size: 12px; color: var(--text-muted); }

.fg { margin-bottom: 14px; position: relative; }
.fg-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px; }

.fg-label {
    display: block; font-size: 11px; font-weight: 700;
    color: var(--text-muted); text-transform: uppercase;
    letter-spacing: .6px; margin-bottom: 6px;
}
.fg-input {
    width: 100%; padding: 10px 13px;
    background: var(--bg-input);
    border: 1px solid var(--border-input);
    border-radius: 9px; font-size: 13px;
    color: var(--text-heading); font-family: inherit;
    transition: all 0.2s;
    box-sizing: border-box;
}
.fg-input:focus {
    outline: none; border-color: #2980b9;
    box-shadow: 0 0 0 3px rgba(41,128,185,0.12);
    background: var(--bg-card);
}
.fg-input[readonly], .fg-input[disabled] { opacity: 0.7; cursor: default; }
.fg-textarea { resize: none; }

.fg-input-wrap { position: relative; }
.fg-check {
    position: absolute; right: 12px; top: 50%;
    transform: translateY(-50%); color: #27ae60; font-size: 13px;
}

.fg-error { display: block; margin-top: 4px; font-size: 11px; color: #e74c3c; font-weight: 600; }

/* Patient dropdown */
.pat-dropdown {
    position: absolute; z-index: 200;
    background: var(--bg-card);
    border: 1px solid var(--border-card);
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    width: 100%; max-height: 200px; overflow-y: auto;
    margin-top: 4px; top: 100%; left: 0;
}
.pat-option {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; cursor: pointer;
    border-bottom: 1px solid var(--border-inner);
    transition: background 0.15s;
}
.pat-option:last-child { border-bottom: none; }
.pat-option:hover { background: var(--bg-input); }
.pat-opt-avatar {
    width: 30px; height: 30px; border-radius: 7px;
    background: linear-gradient(135deg, #2980b9, #1a6ea8);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; flex-shrink: 0;
}
.pat-option strong { display: block; font-size: 13px; color: var(--text-heading); }
.pat-option span   { font-size: 11px; color: var(--text-muted); }

/* SMS row */
.sms-row { margin-bottom: 14px; }
.sms-toggle-label {
    display: flex; align-items: center; gap: 10px;
    cursor: pointer; font-size: 13px; font-weight: 600;
    color: var(--text-heading);
    background: var(--bg-input);
    border: 1px solid var(--border-input);
    border-radius: 9px; padding: 11px 14px;
    user-select: none;
}
.sms-checkbox { display: none; }
.sms-toggle-track {
    width: 36px; height: 20px; background: var(--border-card);
    border-radius: 20px; position: relative;
    transition: background 0.2s; flex-shrink: 0;
}
.sms-checkbox:checked ~ .sms-toggle-track { background: #2980b9; }
.sms-toggle-thumb {
    position: absolute; top: 3px; left: 3px;
    width: 14px; height: 14px; background: #fff;
    border-radius: 50%; transition: left 0.2s;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}
.sms-checkbox:checked ~ .sms-toggle-track .sms-toggle-thumb { left: 19px; }

.sms-preview-block { margin-bottom: 14px; }
.sms-preview-label { margin: 0 0 6px; font-size: 10px; font-weight: 800; letter-spacing: .6px; color: var(--text-muted); }
.sms-preview-bubble {
    background: var(--bg-input); border: 1px solid var(--border-card);
    border-radius: 10px; padding: 12px; font-size: 12px;
    color: var(--text-body); line-height: 1.5;
}

/* Patient History Block */
.patient-history-block {
    margin: 20px 0;
    padding: 16px;
    background: var(--bg-input);
    border: 1px solid var(--border-card);
    border-radius: 10px;
}
.ph-label {
    margin: 0 0 12px;
    font-size: 12px;
    font-weight: 700;
    color: var(--text-heading);
    display: flex;
    align-items: center;
    gap: 8px;
}
.ph-label i { color: #38bdf8; }
.ph-list { display: flex; flex-direction: column; gap: 8px; max-height: 200px; overflow-y: auto; }
.ph-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    background: var(--bg-card);
    border: 1px solid var(--border-inner);
    border-radius: 8px;
    transition: all 0.2s;
}
.ph-item:hover { border-color: rgba(41,128,185,0.3); }
.ph-date { display: flex; flex-direction: column; min-width: 90px; }
.ph-date-main { font-size: 12px; font-weight: 700; color: var(--text-heading); }
.ph-date-time { font-size: 10px; color: var(--text-muted); }
.ph-details { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 4px; }
.ph-reason { font-size: 12px; color: var(--text-body); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ph-status {
    font-size: 10px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
    width: fit-content;
}
.ph-status.status-scheduled { background: rgba(41,128,185,0.15); color: #2980b9; }
.ph-status.status-completed { background: rgba(39,174,96,0.15); color: #27ae60; }
.ph-status.status-no-show { background: rgba(231,76,60,0.15); color: #e74c3c; }
.ph-status.status-cancelled { background: rgba(149,165,166,0.15); color: #95a5a6; }
.ph-delete {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: transparent;
    border: 1px solid var(--border-inner);
    color: var(--text-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}
.ph-delete:hover {
    background: rgba(231,76,60,0.15);
    border-color: #e74c3c;
    color: #e74c3c;
}

/* Schedule button */
.btn-schedule {
    width: 100%; padding: 14px;
    background: linear-gradient(135deg, #2980b9, #1a6ea8);
    color: #fff; border: none; border-radius: 10px;
    font-size: 15px; font-weight: 800; letter-spacing: .5px;
    cursor: pointer; font-family: inherit;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    box-shadow: 0 4px 16px rgba(41,128,185,0.4);
    transition: all 0.2s; margin-bottom: 8px;
}
.btn-schedule:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(41,128,185,0.5); }

.schedule-hint { margin: 0; text-align: center; font-size: 11px; color: var(--text-muted); }

/* Responsive */
@media (max-width: 1024px) {
    .asch-columns { grid-template-columns: 1fr; }
    .asch-right   { position: static; }
}
@media (max-width: 600px) {
    .cal-grid { gap: 4px; }
    .cal-day  { font-size: 11px; border-radius: 7px; }
    .fg-row   { grid-template-columns: 1fr; }
}
@media print { .asch-right { display: none; } }

/* Appointment Detail Modal */
.appt-modal-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5); z-index: 1000;
    display: flex; align-items: center; justify-content: center;
    padding: 20px; animation: fadeIn 0.2s ease;
}
.appt-modal {
    background: var(--bg-card); border-radius: 16px;
    width: 100%; max-width: 500px; max-height: 90vh;
    overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: slideUp 0.3s ease;
}
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

.appt-modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 24px; border-bottom: 1px solid var(--border-inner);
    position: sticky; top: 0; background: var(--bg-card); z-index: 1;
    border-radius: 16px 16px 0 0;
}
.appt-modal-title { margin: 0; font-size: 18px; font-weight: 800; color: var(--text-heading); }
.appt-modal-close {
    background: none; border: none; cursor: pointer;
    color: var(--text-muted); font-size: 20px;
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.2s;
}
.appt-modal-close:hover { background: var(--bg-input); color: var(--text-heading); }

.appt-modal-body { padding: 24px; }
.appt-detail-row { display: flex; gap: 16px; margin-bottom: 16px; }
.appt-detail-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; min-width: 80px; margin-top: 4px; }
.appt-detail-value { font-size: 14px; color: var(--text-heading); font-weight: 500; flex: 1; }
.appt-detail-value i { color: #2980b9; margin-right: 8px; width: 18px; text-align: center; }
.appt-reason-box {
    background: var(--bg-input); border: 1px solid var(--border-card);
    border-radius: 10px; padding: 16px; margin-top: 8px;
}
.appt-reason-label { margin: 0 0 8px; font-size: 11px; font-weight: 800; letter-spacing: .6px; color: var(--text-muted); }
.appt-reason-text { margin: 0; font-size: 14px; color: var(--text-body); line-height: 1.6; white-space: pre-wrap; }
.appt-reason-text i { color: #27ae60; margin-right: 6px; }

.appt-modal-footer {
    padding: 16px 24px 20px;
    border-top: 1px solid var(--border-inner);
    background: var(--bg-card); border-radius: 0 0 16px 16px;
}
.appt-footer-hint {
    margin: 0 0 10px; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--text-muted); display: flex; align-items: center; gap: 6px;
}
.appt-footer-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.appt-footer-readonly { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.btn-appt-action {
    flex: 1; min-width: 110px; padding: 12px 16px; border: none;
    border-radius: 10px; font-family: inherit; cursor: pointer;
    display: flex; align-items: center; gap: 10px;
    transition: all 0.2s; text-align: left;
}
.btn-appt-action i { font-size: 18px; flex-shrink: 0; }
.btn-appt-action span { display: flex; flex-direction: column; gap: 2px; }
.btn-appt-action strong { font-size: 13px; font-weight: 700; line-height: 1; }
.btn-appt-action small { font-size: 10px; opacity: .8; font-weight: 500; }
.btn-check {
    background: linear-gradient(135deg, #27ae60, #1e8a49);
    color: #fff; box-shadow: 0 4px 12px rgba(39,174,96,0.35);
}
.btn-check:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(39,174,96,0.45); }
.btn-no-show {
    background: linear-gradient(135deg, #f39c12, #d68910);
    color: #fff; box-shadow: 0 4px 12px rgba(243,156,18,0.35);
}
.btn-no-show:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(243,156,18,0.45); }
.btn-cancel-appt {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: #fff; box-shadow: 0 4px 12px rgba(231,76,60,0.35);
}
.btn-cancel-appt:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(231,76,60,0.45); }
</style>
</div>{{-- close .asch-page root div --}}
