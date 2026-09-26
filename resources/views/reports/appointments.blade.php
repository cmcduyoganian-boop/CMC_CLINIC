<x-app-with-sidebar>
    <x-slot name="header">Appointments Report</x-slot>

    <div class="report-page">

        {{-- ── Header ── --}}
        <div class="report-header">
            <div class="header-actions">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ route('reports.download', ['type' => 'appointments', 'date' => $date ?? '', 'preset' => $preset ?? '']) }}" class="btn btn-download">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        {{-- ── Date Filter Bar ── --}}
        <div class="date-filter-bar">
            <form method="GET" action="{{ route('reports.appointments') }}" class="date-filter-form">
                <div class="dff-presets">
                    <a href="{{ route('reports.appointments') }}"                              class="dff-preset {{ !($date ?? null) && !($preset ?? null) ? 'active' : '' }}">All Time</a>
                    <a href="{{ route('reports.appointments', ['preset' => 'today']) }}"       class="dff-preset {{ ($preset ?? '') === 'today'  ? 'active' : '' }}">Today</a>
                    <a href="{{ route('reports.appointments', ['preset' => 'week']) }}"        class="dff-preset {{ ($preset ?? '') === 'week'   ? 'active' : '' }}">This Week</a>
                    <a href="{{ route('reports.appointments', ['preset' => 'month']) }}"       class="dff-preset {{ ($preset ?? '') === 'month'  ? 'active' : '' }}">This Month</a>
                </div>
                <div class="dff-range">
                    <span class="dff-label"><i class="fas fa-calendar-alt"></i> Filter by Date:</span>
                    <input type="date" name="date" value="{{ $date ?? '' }}" class="dff-input" max="{{ date('Y-m-d') }}">
                    <button type="submit" class="dff-apply"><i class="fas fa-filter"></i> Apply</button>
                    @if(!empty($date) || !empty($preset))
                        <a href="{{ route('reports.appointments') }}" class="dff-clear"><i class="fas fa-times"></i> Clear</a>
                    @endif
                </div>
            </form>
            @if(!empty($date) || !empty($preset))
                <div class="dff-result-info">
                    <i class="fas fa-info-circle"></i>
                    @if(!empty($date))
                        Showing <strong>{{ $filteredCount ?? 0 }}</strong> appointment(s) on <strong>{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</strong>
                    @else
                        Showing <strong>{{ $filteredCount ?? 0 }}</strong> appointment(s) — <strong>{{ ucfirst($preset) }}</strong>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── Summary Cards ── --}}
        <div class="appt-summary-grid">
            <div class="appt-card appt-total clickable-card" data-filter="all">
                <div class="appt-card-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">Total Appointments</p>
                    <p class="appt-card-value">{{ $totalAppointments }}</p>
                </div>
            </div>
            <div class="appt-card appt-scheduled clickable-card" data-filter="scheduled">
                <div class="appt-card-icon"><i class="fas fa-clock"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">Scheduled</p>
                    <p class="appt-card-value">{{ $scheduled }}</p>
                </div>
            </div>
            <div class="appt-card appt-completed clickable-card" data-filter="completed">
                <div class="appt-card-icon"><i class="fas fa-check-circle"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">Completed</p>
                    <p class="appt-card-value">{{ $completed }}</p>
                </div>
            </div>
            <div class="appt-card appt-noshow clickable-card" data-filter="no-show">
                <div class="appt-card-icon"><i class="fas fa-user-times"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">No-Show</p>
                    <p class="appt-card-value">{{ $noShow }}</p>
                </div>
            </div>
            <div class="appt-card appt-cancelled clickable-card" data-filter="cancelled">
                <div class="appt-card-icon"><i class="fas fa-times-circle"></i></div>
                <div class="appt-card-body">
                    <p class="appt-card-label">Cancelled</p>
                    <p class="appt-card-value">{{ $cancelled ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- ── Chart + Stats row ── --}}
        <div class="appt-chart-row">
            <div class="appt-chart-card">
                <h2 class="section-title"><i class="fas fa-chart-pie"></i> Status Distribution</h2>
                <div class="donut-wrap">
                    <canvas id="statusChart"
                        data-scheduled="{{ $scheduled }}"
                        data-completed="{{ $completed }}"
                        data-noshow="{{ $noShow }}"
                        data-cancelled="{{ $cancelled ?? 0 }}">
                    </canvas>
                </div>
            </div>

            <div class="appt-stats-card" style="--sched-rate: {{ $schedRate }}%; --comp-rate: {{ $compRate }}%; --noshow-rate: {{ $noShowRate }}%; --cancel-rate: {{ $canxRate }}%;">
                <h2 class="section-title"><i class="fas fa-info-circle"></i> Quick Stats</h2>
                <div class="stat-row">
                    <span class="stat-label"><span class="stat-dot dot-blue"></span>Scheduled</span>
                    <div class="stat-bar-wrap">
                        <div class="stat-bar stat-bar-scheduled"></div>
                    </div>
                    <span class="stat-pct">{{ $schedRate }}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label"><span class="stat-dot dot-green"></span>Completed</span>
                    <div class="stat-bar-wrap">
                        <div class="stat-bar stat-bar-completed"></div>
                    </div>
                    <span class="stat-pct">{{ $compRate }}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label"><span class="stat-dot dot-yellow"></span>No-Show</span>
                    <div class="stat-bar-wrap">
                        <div class="stat-bar stat-bar-noshow"></div>
                    </div>
                    <span class="stat-pct">{{ $noShowRate }}%</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label"><span class="stat-dot dot-red"></span>Cancelled</span>
                    <div class="stat-bar-wrap">
                        <div class="stat-bar stat-bar-cancelled"></div>
                    </div>
                    <span class="stat-pct">{{ $canxRate }}%</span>
                </div>

                <div class="completion-highlight">
                    <i class="fas fa-trophy"></i>
                    <div>
                        <p class="ch-label">Completion Rate</p>
                        <p class="ch-value">{{ $compRate }}%</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Appointments Table ── --}}
        <div class="appt-table-card">
            <div class="table-topbar">
                <h2 class="section-title" style="margin:0"><i class="fas fa-list"></i> Appointments List</h2>
                <button id="clearFilterBtn" class="clear-filter-btn" style="display:none;">
                    <i class="fas fa-times-circle"></i> Clear Filter
                </button>
            </div>

            @if($appointments->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>No appointments found for the selected period.</p>
                </div>
            @else
                <div class="table-scroll">
                    <table class="appt-table">
                        <colgroup>
                            <col style="width:140px">
                            <col>
                            <col style="width:110px">
                            <col>
                            <col style="width:110px">
                            <col style="width:70px">
                        </colgroup>
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-user"></i> Patient</th>
                                <th><i class="fas fa-tag"></i> Category</th>
                                <th><i class="fas fa-comment-medical"></i> Reason</th>
                                <th><i class="fas fa-circle-dot"></i> Status</th>
                                <th><i class="fas fa-clock"></i> Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appt)
                                @php $filters = ['all', strtolower($appt->status)]; @endphp
                                <tr class="data-row clickable-row" data-filters="{{ implode(',', $filters) }}" 
                                    data-id="{{ $appt->id }}"
                                    data-patient="{{ $appt->patient->name ?? '' }}"
                                    data-category="{{ $appt->patient->category ?? '' }}"
                                    data-date="{{ $appt->appointment_date }}"
                                    data-time="{{ $appt->appointment_time }}"
                                    data-reason="{{ $appt->reason ?? '' }}"
                                    data-notes="{{ $appt->notes ?? '' }}"
                                    data-status="{{ $appt->status }}">
                                    <td>
                                        <span class="date-main">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="patient-cell">
                                            <span class="patient-avatar">{{ strtoupper(substr($appt->patient->name ?? '?', 0, 1)) }}</span>
                                            <div>
                                                <p class="patient-name">{{ $appt->patient->name ?? '—' }}</p>
                                                @if($appt->patient->year_section)
                                                    <p class="patient-sub">{{ $appt->patient->year_section }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="cat-badge cat-{{ $appt->patient->category ?? 'other' }}">
                                            {{ ucfirst($appt->patient->category ?? '—') }}
                                        </span>
                                    </td>
                                    <td class="reason-cell">{{ $appt->reason ?? '—' }}</td>
                                    <td>
                                        <span class="status-pill status-{{ $appt->status }}">
                                            @if($appt->status === 'scheduled')   <i class="fas fa-clock"></i>
                                            @elseif($appt->status === 'completed') <i class="fas fa-check"></i>
                                            @elseif($appt->status === 'no-show')   <i class="fas fa-user-times"></i>
                                            @else <i class="fas fa-times"></i>
                                            @endif
                                            {{ ucfirst($appt->status) }}
                                        </span>
                                    </td>
                                    <td class="time-cell">
                                        @if($appt->appointment_time)
                                            {{ date('h:i A', strtotime($appt->appointment_time)) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ── Appointment Detail Modal ── --}}
        <div id="apptModal" class="appt-modal-overlay" style="display:none;">
            <div class="appt-modal">
                <div class="appt-modal-header">
                    <h3 class="appt-modal-title">Appointment Details</h3>
                    <button type="button" class="appt-modal-close" onclick="closeApptModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="appt-modal-body">
                    <div class="appt-detail-row">
                        <span class="appt-detail-label">Patient</span>
                        <span class="appt-detail-value" id="modalPatient"><i class="fas fa-user"></i> —</span>
                    </div>
                    <div class="appt-detail-row">
                        <span class="appt-detail-label">Category</span>
                        <span class="appt-detail-value" id="modalCategory"><i class="fas fa-tag"></i> —</span>
                    </div>
                    <div class="appt-detail-row">
                        <span class="appt-detail-label">Date</span>
                        <span class="appt-detail-value" id="modalDate"><i class="far fa-calendar-alt"></i> —</span>
                    </div>
                    <div class="appt-detail-row">
                        <span class="appt-detail-label">Time</span>
                        <span class="appt-detail-value" id="modalTime"><i class="far fa-clock"></i> —</span>
                    </div>
                    <div class="appt-detail-row">
                        <span class="appt-detail-label">Status</span>
                        <span class="appt-detail-value" id="modalStatus">—</span>
                    </div>
                    <div class="appt-reason-box" id="modalReasonBox" style="display:none;">
                        <p class="appt-reason-label"><i class="fas fa-stethoscope"></i> Reason for Visit</p>
                        <p class="appt-reason-text" id="modalReason">—</p>
                    </div>
                    <div class="appt-reason-box" id="modalNotesBox" style="display:none; margin-top:12px;">
                        <p class="appt-reason-label"><i class="fas fa-sticky-note"></i> Notes</p>
                        <p class="appt-reason-text" id="modalNotes">—</p>
                    </div>
                </div>
                <div class="appt-modal-footer" id="modalFooter" style="display:none;">
                    <button type="button" class="btn-appt-action btn-check" onclick="updateApptStatus('completed')">
                        <i class="fas fa-check"></i> Check (Arrived)
                    </button>
                    <button type="button" class="btn-appt-action btn-no-show" onclick="updateApptStatus('no-show')">
                        <i class="fas fa-times"></i> No Show
                    </button>
                    <button type="button" class="btn-appt-action btn-cancel" onclick="updateApptStatus('cancelled')">
                        <i class="fas fa-ban"></i> Cancel
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Footer ── --}}
        <div class="report-foot">
            <i class="fas fa-hospital"></i>
            CMC Clinic Management System &nbsp;·&nbsp; Generated {{ now()->format('F d, Y \a\t h:i A') }}
        </div>

    </div>

    {{-- ── Chart JS ── --}}
    <script>
    (function() {
        const canvas = document.getElementById('statusChart');
        if (!canvas) return;
        const s = parseInt(canvas.dataset.scheduled  || 0);
        const c = parseInt(canvas.dataset.completed  || 0);
        const n = parseInt(canvas.dataset.noshow     || 0);
        const x = parseInt(canvas.dataset.cancelled  || 0);

        new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Scheduled', 'Completed', 'No-Show', 'Cancelled'],
                datasets: [{
                    data: [s, c, n, x],
                    backgroundColor: ['#38bdf8', '#27ae60', '#f39c12', '#e74c3c'],
                    borderColor: 'transparent',
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#94a3b8', font: { size: 12 }, padding: 16 }
                    }
                }
            }
        });
    })();

    // Card filter click
    document.addEventListener('DOMContentLoaded', function () {
        const cards   = document.querySelectorAll('.clickable-card');
        const rows    = document.querySelectorAll('.data-row');
        const clearBtn = document.getElementById('clearFilterBtn');
        let active = null;

        cards.forEach(card => {
            card.addEventListener('click', function () {
                const f = this.dataset.filter;
                if (active === f) {
                    active = null;
                    cards.forEach(c => c.classList.remove('is-active'));
                    rows.forEach(r => r.classList.remove('hidden'));
                    if (clearBtn) clearBtn.style.display = 'none';
                } else {
                    active = f;
                    cards.forEach(c => c.classList.remove('is-active'));
                    this.classList.add('is-active');
                    rows.forEach(r => {
                        const rf = r.dataset.filters.split(',');
                        r.classList.toggle('hidden', !rf.includes(f));
                    });
                    if (clearBtn) clearBtn.style.display = 'flex';
                }
            });
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                active = null;
                cards.forEach(c => c.classList.remove('is-active'));
                rows.forEach(r => r.classList.remove('hidden'));
                this.style.display = 'none';
            });
        }

        // Appointment row click - show modal
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function(e) {
                // Don't open modal if clicking on a link/button inside
                if (e.target.closest('a, button, .status-pill')) return;
                openApptModal(this);
            });
        });
    });

    function openApptModal(row) {
        const modal = document.getElementById('apptModal');
        document.getElementById('modalPatient').innerHTML = '<i class="fas fa-user"></i> ' + (row.dataset.patient || '—');
        document.getElementById('modalCategory').innerHTML = '<i class="fas fa-tag"></i> ' + (row.dataset.category ? row.dataset.category.charAt(0).toUpperCase() + row.dataset.category.slice(1) : '—');
        
        const date = row.dataset.date ? new Date(row.dataset.date) : null;
        document.getElementById('modalDate').innerHTML = '<i class="far fa-calendar-alt"></i> ' + (date ? date.toLocaleDateString('en-US', {year:'numeric', month:'long', day:'numeric'}) : '—');
        
        document.getElementById('modalTime').innerHTML = '<i class="far fa-clock"></i> ' + (row.dataset.time ? formatTime(row.dataset.time) : '—');
        
        const status = row.dataset.status;
        const statusLabels = { scheduled: 'Scheduled', completed: 'Completed', 'no-show': 'No-Show', cancelled: 'Cancelled' };
        const statusIcons = { scheduled: 'fa-clock', completed: 'fa-check', 'no-show': 'fa-user-times', cancelled: 'fa-times' };
        document.getElementById('modalStatus').innerHTML = '<span class="status-pill status-' + status + '"><i class="fas ' + (statusIcons[status] || 'fa-circle') + '"></i> ' + (statusLabels[status] || status) + '</span>';
        
        const reason = row.dataset.reason;
        const reasonBox = document.getElementById('modalReasonBox');
        if (reason && reason !== '—') {
            document.getElementById('modalReason').textContent = reason;
            reasonBox.style.display = 'block';
        } else {
            reasonBox.style.display = 'none';
        }
        
        const notes = row.dataset.notes;
        const notesBox = document.getElementById('modalNotesBox');
        if (notes && notes !== '—') {
            document.getElementById('modalNotes').textContent = notes;
            notesBox.style.display = 'block';
        } else {
            notesBox.style.display = 'none';
        }
        
        const footer = document.getElementById('modalFooter');
        footer.style.display = (status === 'scheduled') ? 'flex' : 'none';
        footer.dataset.appointmentId = row.dataset.id;
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeApptModal() {
        const modal = document.getElementById('apptModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    
    function formatTime(timeStr) {
        const [h, m] = timeStr.split(':');
        const hour = parseInt(h);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return hour12 + ':' + m + ' ' + ampm;
    }
    
    function updateApptStatus(newStatus) {
        const footer = document.getElementById('modalFooter');
        const appointmentId = footer.dataset.appointmentId;
        if (!appointmentId) return;
        
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        
        fetch('/reports/appointment/' + appointmentId + '/status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the row in the table
                const row = document.querySelector('.clickable-row[data-id="' + appointmentId + '"]');
                if (row) {
                    row.dataset.status = newStatus;
                    const statusLabels = { completed: 'Completed', 'no-show': 'No-Show', cancelled: 'Cancelled' };
                    const statusIcons = { completed: 'fa-check', 'no-show': 'fa-user-times', cancelled: 'fa-ban' };
                    row.querySelector('.status-pill').className = 'status-pill status-' + newStatus;
                    row.querySelector('.status-pill').innerHTML = '<i class="fas ' + statusIcons[newStatus] + '"></i> ' + statusLabels[newStatus];
                }
                closeApptModal();
                showToast('Appointment marked as ' + statusLabels[newStatus] + '!', 'success');
            } else {
                showToast(data.message || 'Failed to update status', 'error');
            }
        })
        .catch(err => {
            showToast('Error updating appointment', 'error');
            console.error(err);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
    
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        toast.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Close modal on overlay click
    document.getElementById('apptModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeApptModal();
    });
    </script>

    <style>
        /* ── Page Layout ──────────────────────────────────────────── */
        .report-page { display: flex; flex-direction: column; gap: 22px; }

        .report-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border-radius: 8px; border: none;
            font-size: 13px; font-weight: 700; cursor: pointer;
            text-decoration: none; transition: opacity 0.2s;
            font-family: inherit;
        }
        .btn:hover { opacity: 0.85; }
        .btn-print    { background: #2980b9;              color: #fff; }
        .btn-download { background: #27ae60;              color: #fff; }
        .btn-back     { background: var(--bg-input);      color: var(--text-body); border: 1px solid var(--border-card); }

        /* ── Summary Cards ────────────────────────────────────────── */
        .appt-summary-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }

        .appt-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            padding: 18px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }
        .appt-card:hover  { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .appt-card.is-active { box-shadow: 0 4px 16px rgba(56,189,248,0.2); }

        .appt-total     { border-left-color: #38bdf8; }
        .appt-scheduled { border-left-color: #38bdf8; }
        .appt-completed { border-left-color: #27ae60; }
        .appt-noshow    { border-left-color: #f39c12; }
        .appt-cancelled { border-left-color: #e74c3c; }

        .appt-total.is-active     { background: rgba(56,189,248,0.07); }
        .appt-scheduled.is-active { background: rgba(56,189,248,0.07); }
        .appt-completed.is-active { background: rgba(39,174,96,0.07);  }
        .appt-noshow.is-active    { background: rgba(243,156,18,0.07); }
        .appt-cancelled.is-active { background: rgba(231,76,60,0.07);  }

        .appt-card-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .appt-total     .appt-card-icon { background: rgba(56,189,248,0.15); color: #38bdf8; }
        .appt-scheduled .appt-card-icon { background: rgba(56,189,248,0.15); color: #38bdf8; }
        .appt-completed .appt-card-icon { background: rgba(39,174,96,0.15);  color: #27ae60; }
        .appt-noshow    .appt-card-icon { background: rgba(243,156,18,0.15); color: #f39c12; }
        .appt-cancelled .appt-card-icon { background: rgba(231,76,60,0.15);  color: #e74c3c; }

        .appt-card-label {
            margin: 0; font-size: 10px; font-weight: 700;
            text-transform: uppercase; color: var(--text-muted); letter-spacing: .5px;
        }
        .appt-card-value {
            margin: 4px 0 0; font-size: 26px; font-weight: 800;
            color: var(--text-heading);
        }

        /* ── Chart + Stats row ────────────────────────────────────── */
        .appt-chart-row {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
        }

        .appt-chart-card,
        .appt-stats-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            padding: 22px;
        }

        .section-title {
            margin: 0 0 18px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-inner);
        }
        .section-title i { color: #38bdf8; }

        .donut-wrap {
            position: relative;
            height: 220px;
        }

        /* ── Stat bars ────────────────────────────────────────────── */
        .stat-row {
            display: grid;
            grid-template-columns: 110px 1fr 40px;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .stat-label {
            font-size: 12px;
            color: var(--text-body);
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .stat-dot {
            width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        }
        .dot-blue   { background: #38bdf8; }
        .dot-green  { background: #27ae60; }
        .dot-yellow { background: #f39c12; }
        .dot-red    { background: #e74c3c; }

        .stat-bar-wrap {
            height: 8px; background: var(--bg-input);
            border-radius: 4px; overflow: hidden;
        }
        .stat-bar { height: 100%; border-radius: 4px; transition: width 0.6s ease; }
        .stat-bar-scheduled  { width: var(--sched-rate, 0%);  background: #38bdf8; }
        .stat-bar-completed  { width: var(--comp-rate, 0%);   background: #27ae60; }
        .stat-bar-noshow     { width: var(--noshow-rate, 0%);  background: #f39c12; }
        .stat-bar-cancelled  { width: var(--cancel-rate, 0%);  background: #e74c3c; }
        .stat-pct { font-size: 11px; font-weight: 700; color: var(--text-muted); text-align: right; }

        .completion-highlight {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 22px;
            padding: 14px 18px;
            background: linear-gradient(135deg, rgba(39,174,96,0.12), rgba(56,189,248,0.08));
            border: 1px solid rgba(39,174,96,0.3);
            border-radius: 10px;
        }
        .completion-highlight i { font-size: 22px; color: #f39c12; }
        .ch-label { margin: 0; font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
        .ch-value { margin: 2px 0 0; font-size: 24px; font-weight: 800; color: #27ae60; }

        /* ── Table ────────────────────────────────────────────────── */
        .appt-table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            padding: 22px;
        }

        .table-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .clear-filter-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(231,76,60,0.12); color: #e74c3c;
            border: 1px solid rgba(231,76,60,0.3);
            border-radius: 6px; padding: 6px 14px;
            font-size: 12px; font-weight: 700; cursor: pointer;
            font-family: inherit; transition: background 0.2s;
        }
        .clear-filter-btn:hover { background: rgba(231,76,60,0.22); }

        .table-scroll { overflow-x: auto; }

        .appt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .appt-table thead tr {
            background: linear-gradient(135deg, #2980b9, #1a6ea8);
        }

        .appt-table th {
            padding: 12px 14px;
            text-align: left;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        .appt-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-inner);
            color: var(--text-heading);
            vertical-align: middle;
        }

        .appt-table tbody tr:hover { background: var(--bg-input); }
        .appt-table tbody tr:last-child td { border-bottom: none; }

        .data-row.hidden { display: none; }

        /* Date cell */
        .date-main { font-size: 12px; font-weight: 600; color: var(--text-body); }

        /* Patient cell */
        .patient-cell { display: flex; align-items: center; gap: 10px; }
        .patient-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: #fff; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .patient-name { margin: 0; font-size: 13px; font-weight: 700; color: var(--text-heading); }
        .patient-sub  { margin: 2px 0 0; font-size: 11px; color: var(--text-muted); }

        /* Category badges */
        .cat-badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700; white-space: nowrap;
        }
        .cat-student  { background: rgba(56,189,248,0.15);  color: #38bdf8; }
        .cat-faculty  { background: rgba(139,92,246,0.15); color: #8b5cf6; }
        .cat-staff    { background: rgba(39,174,96,0.15);  color: #27ae60; }
        .cat-other    { background: rgba(100,116,139,0.15); color: #94a3b8; }

        /* Status pills */
        .status-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700; white-space: nowrap;
        }
        .status-scheduled  { background: rgba(56,189,248,0.15); color: #38bdf8;  border: 1px solid rgba(56,189,248,0.3); }
        .status-completed  { background: rgba(39,174,96,0.15);  color: #27ae60;  border: 1px solid rgba(39,174,96,0.3);  }
        .status-no-show    { background: rgba(243,156,18,0.15); color: #f39c12;  border: 1px solid rgba(243,156,18,0.3); }
        .status-cancelled  { background: rgba(231,76,60,0.15);  color: #e74c3c;  border: 1px solid rgba(231,76,60,0.3);  }

        .reason-cell { font-size: 12px; color: var(--text-body); max-width: 200px; }
        .time-cell   { font-size: 12px; color: var(--text-muted); white-space: nowrap; }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 48px 24px; color: var(--text-muted);
        }
        .empty-state i { font-size: 36px; margin-bottom: 12px; display: block; color: var(--border-inner); }
        .empty-state p { margin: 0; font-size: 14px; }

        /* Footer */
        .report-foot {
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            padding: 12px 0;
            border-top: 1px solid var(--border-inner);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }

        /* ── Responsive ───────────────────────────────────────────── */
        @media (max-width: 1024px) {
            .appt-summary-grid { grid-template-columns: repeat(3, 1fr); }
            .appt-chart-row    { grid-template-columns: 1fr; }
            .donut-wrap        { height: 200px; }
        }
        @media (max-width: 600px) {
            .appt-summary-grid { grid-template-columns: repeat(2, 1fr); }
            .header-actions    { flex-wrap: wrap; }
        }

        @media print {
            .header-actions, .clear-filter-btn, .date-filter-bar { display: none !important; }
        }

        /* ── Clickable Row ─────────────────────────────────────────── */
        .clickable-row { transition: background 0.15s; }
        .clickable-row:hover { background: var(--bg-input) !important; }

        /* ── Appointment Detail Modal ── */
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
            display: flex; gap: 12px; padding: 20px 24px;
            border-top: 1px solid var(--border-inner);
            background: var(--bg-card); border-radius: 0 0 16px 16px;
        }
        .btn-appt-action {
            flex: 1; padding: 14px 20px; border: none; border-radius: 10px;
            font-size: 14px; font-weight: 800; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.2s;
        }
        .btn-check {
            background: linear-gradient(135deg, #27ae60, #1e8a49);
            color: #fff; box-shadow: 0 4px 14px rgba(39,174,96,0.4);
        }
        .btn-check:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(39,174,96,0.5); }
        .btn-no-show {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff; box-shadow: 0 4px 14px rgba(231,76,60,0.4);
        }
        .btn-no-show:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(231,76,60,0.5); }
        .btn-cancel {
            background: linear-gradient(135deg, #f39c12, #d68910);
            color: #fff; box-shadow: 0 4px 14px rgba(243,156,18,0.4);
        }
        .btn-cancel:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(243,156,18,0.5); }

        /* ── Toast ── */
        .toast {
            position: fixed; bottom: 24px; right: 24px; z-index: 1100;
            display: flex; align-items: center; gap: 10px;
            padding: 14px 20px; border-radius: 10px;
            font-size: 13px; font-weight: 600; color: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transform: translateY(100px); opacity: 0;
            transition: all 0.3s ease;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast-success { background: linear-gradient(135deg, #27ae60, #1e8a49); }
        .toast-error { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    </style>

</x-app-with-sidebar>