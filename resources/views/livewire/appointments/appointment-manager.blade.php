<div class="appointment-manager">
    <!-- Header -->
    <div class="manager-header">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📅 Appointments</h1>
            <p class="text-gray-600 mt-1">Manage patient appointments and follow-ups</p>
        </div>
        <button wire:click="openForm" class="btn-primary">
            ➕ Schedule Appointment
        </button>
    </div>

    <!-- Stats -->
    <div class="stats-grid mt-6">
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <div class="stat-number">{{ $scheduledCount }}</div>
                <div class="stat-label">Scheduled</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✓</div>
            <div class="stat-content">
                <div class="stat-number">{{ $completedCount }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✕</div>
            <div class="stat-content">
                <div class="stat-number">{{ $missedCount }}</div>
                <div class="stat-label">Missed</div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card mt-6">
        <div class="card-filters">
            <input type="text"
                   wire:model.live="searchTerm"
                   placeholder="Search patient name..."
                   class="filter-input">
            
            <select wire:model.live="filterStatus" class="filter-select">
                <option value="">All Status</option>
                <option value="scheduled">Scheduled</option>
                <option value="completed">Completed</option>
                <option value="missed">Missed</option>
            </select>

            <input type="date"
                   wire:model.live="filterDate"
                   class="filter-input">
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="card mt-6">
        @if($appointments->count() > 0)
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Appointment Date & Time</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr class="table-row">
                                <td class="font-semibold">{{ $appointment->patient->name }}</td>
                                <td class="text-sm">
                                    {{ $appointment->appointment_date->format('M d, Y - H:i') }}
                                </td>
                                <td>
                                    @if($appointment->status === 'scheduled')
                                        <span class="badge badge-blue">📅 Scheduled</span>
                                    @elseif($appointment->status === 'completed')
                                        <span class="badge badge-green">✓ Completed</span>
                                    @else
                                        <span class="badge badge-red">✕ Missed</span>
                                    @endif
                                </td>
                                <td class="text-sm text-gray-600">
                                    {{ $appointment->notes ? Str::limit($appointment->notes, 40) : '—' }}
                                </td>
                                <td class="action-buttons">
                                    @if($appointment->status === 'scheduled')
                                        <button wire:click="markCompleted({{ $appointment->id }})"
                                                class="btn btn-success"
                                                title="Mark as completed">
                                            ✓
                                        </button>
                                        <button wire:click="markMissed({{ $appointment->id }})"
                                                class="btn btn-warning"
                                                title="Mark as missed">
                                            ✕
                                        </button>
                                    @endif
                                    <button wire:click="editAppointment({{ $appointment->id }})"
                                            class="btn btn-edit">
                                        ✏️
                                    </button>
                                    <button wire:click="deleteAppointment({{ $appointment->id }})"
                                            class="btn btn-delete"
                                            onclick="confirm('Delete this appointment?') || event.preventDefault()">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $appointments->links() }}
            </div>
        @else
            <div class="empty-state">
                <p>📭 No appointments found.</p>
            </div>
        @endif
    </div>

    <!-- Schedule Appointment Modal -->
    @if($showForm)
        <div class="modal-overlay" wire:click="closeForm">
            <div class="modal-content" @click.stop>
                <div class="modal-header">
                    <h2>{{ $appointmentId ? '✏️ Edit Appointment' : '➕ Schedule New Appointment' }}</h2>
                    <button wire:click="closeForm" class="modal-close">✕</button>
                </div>

                <form wire:submit="saveAppointment">
                    <div class="form-group">
                        <label>Patient *</label>
                        <select wire:model="patient_id"
                                class="form-input {{ $errors->has('patient_id') ? 'error' : '' }}">
                            <option value="">Select a patient</option>
                            @foreach($approvedPatients as $patient)
                                <option value="{{ $patient->id }}">
                                    {{ $patient->name }} ({{ $patient->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Appointment Date & Time *</label>
                        <input type="datetime-local"
                               wire:model="appointment_date"
                               class="form-input {{ $errors->has('appointment_date') ? 'error' : '' }}">
                        @error('appointment_date')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Status *</label>
                        <select wire:model="status"
                                class="form-input {{ $errors->has('status') ? 'error' : '' }}">
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="missed">Missed</option>
                        </select>
                        @error('status')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea wire:model="notes"
                                  placeholder="Add any notes about the appointment (optional)"
                                  rows="4"
                                  class="form-input"></textarea>
                        @error('notes')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-actions">
                        <button type="button" wire:click="closeForm" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">
                            {{ $appointmentId ? '💾 Update' : '➕ Schedule' }} Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <style>
        .appointment-manager { padding: 0; }

        .manager-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .text-3xl { font-size: 28px; font-weight: 700; color: var(--text-heading); margin: 0; }
        .text-gray-600 { font-size: 13px; color: var(--text-body); margin: 4px 0 0 0; }
        .mt-1 { margin-top: 4px; }
        .mt-6 { margin-top: 20px; }
        .font-semibold { font-weight: 600; color: var(--text-heading); }
        .text-sm { font-size: 12px; color: var(--text-body); }
        .text-gray-600 { color: var(--text-body); }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        }

        .stat-card:hover { transform: translateY(-2px); }

        .stat-icon {
            font-size: 22px;
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-heading);
            line-height: 1;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 4px;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .card-filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-input, .filter-select {
            border: 1px solid var(--border-input);
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
            background: var(--bg-input);
            color: var(--text-heading);
        }

        .filter-input:focus, .filter-select:focus {
            border-color: #38bdf8;
            outline: none;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
        }

        .filter-input { flex: 1; min-width: 150px; }

        .table-responsive { overflow-x: auto; }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dashboard-table thead th {
            background: transparent;
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 2px solid var(--border-inner);
        }

        .table-row { border-bottom: 1px solid var(--border-inner); }
        .table-row:hover { background: var(--bg-input); }
        .table-row td { padding: 12px 16px; font-size: 13px; color: var(--text-body); }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-blue  { background: rgba(56, 189, 248, 0.1);  color: #38bdf8; }
        .badge-green { background: rgba(39, 174, 96, 0.1);   color: #27ae60; }
        .badge-red   { background: rgba(231, 76, 60, 0.1);   color: #e74c3c; }

        .action-buttons { display: flex; gap: 6px; }

        .btn {
            padding: 6px 10px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.15s;
        }

        .btn-success { background: rgba(39,174,96,0.12); color: #27ae60; }
        .btn-success:hover { background: rgba(39,174,96,0.2); }
        .btn-warning { background: rgba(243,156,18,0.12); color: #f39c12; }
        .btn-warning:hover { background: rgba(243,156,18,0.2); }
        .btn-edit { background: rgba(56,189,248,0.12); color: #38bdf8; }
        .btn-edit:hover { background: rgba(56,189,248,0.2); }
        .btn-delete { background: rgba(231,76,60,0.12); color: #e74c3c; }
        .btn-delete:hover { background: rgba(231,76,60,0.2); }

        .btn-primary {
            padding: 10px 20px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-primary:hover { opacity: 0.9; transform: translateY(-2px); }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 10px;
            padding: 0;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-inner);
        }

        .modal-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .modal-close {
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            border-radius: 6px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.15s;
        }

        .modal-close:hover { border-color: #e74c3c; color: #e74c3c; }

        .form-group {
            padding: 16px 24px 0;
            margin-bottom: 4px;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            border: 1px solid var(--border-input);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 13px;
            background: var(--bg-input);
            color: var(--text-heading);
            transition: border-color 0.2s;
        }

        .form-input:focus { border-color: #38bdf8; outline: none; box-shadow: 0 0 0 3px rgba(56,189,248,0.1); }
        .form-input.error { border-color: #e74c3c; }
        .error-text { font-size: 11px; color: #e74c3c; margin-top: 4px; display: block; }

        .form-actions {
            padding: 16px 24px;
            border-top: 1px solid var(--border-inner);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-secondary {
            padding: 10px 20px;
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            color: var(--text-body);
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-secondary:hover { border-color: #38bdf8; color: #38bdf8; }

        @media (max-width: 768px) {
            .manager-header { flex-direction: column; gap: 12px; }
            .card-filters { flex-direction: column; }
            .filter-input { min-width: 100%; }
        }
    </style>
</div>