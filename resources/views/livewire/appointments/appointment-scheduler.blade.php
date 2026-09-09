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
                        <div class="upc-item">
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

                    {{-- Submit --}}
                    <button type="submit" class="btn-schedule">
                        <i class="fas fa-calendar-check"></i> SCHEDULE
                    </button>
                    <p class="schedule-hint">Appointment confirmation will be sent to the patient immediately.</p>
                </form>
            </div>
        </div>
    </div>
</div>

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

.upc-badge {
    font-size: 10px; font-weight: 800; padding: 3px 10px;
    border-radius: 20px; white-space: nowrap; flex-shrink: 0;
}
.badge-today    { background: rgba(39,174,96,0.12); color: #27ae60; border: 1px solid rgba(39,174,96,0.25); }
.badge-upcoming { background: rgba(41,128,185,0.12); color: #2980b9; border: 1px solid rgba(41,128,185,0.2); }

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
</style>
