@extends('layouts.app-with-sidebar')

@section('content')
<div class="recently-deleted-page">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Recently Deleted</h1>
            <p class="page-subtitle">Recover records that were deleted recently.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @php
        $hasDeletedItems = $deletedPatients->isNotEmpty() || $deletedVisits->isNotEmpty() || $deletedAppointments->isNotEmpty();
    @endphp

    @if (!$hasDeletedItems)
        <div class="empty-state-box">
            <i class="fas fa-trash-alt"></i>
            <p>No recently deleted records found.</p>
        </div>
    @else
        <div class="bulk-actions-bar">
            <form method="POST" action="{{ route('recently-deleted.clear-all') }}" onsubmit="return confirm('This will permanently delete all deleted records from the recycle list. This cannot be undone.');">
                @csrf
                <button type="submit" class="bulk-clear-btn"><i class="fas fa-broom"></i> Clear All Deleted</button>
            </form>
        </div>

        <div class="deleted-section">
            <h2>Patients</h2>
            @if ($deletedPatients->isEmpty())
                <div class="section-empty">No deleted patients.</div>
            @else
                <div class="records-grid">
                    @foreach ($deletedPatients as $patient)
                        <div class="deleted-card">
                            <div class="deleted-card-header">
                                <div>
                                    <strong>{{ $patient->name }}</strong>
                                    <small>{{ $patient->email ?? 'No email' }}</small>
                                </div>
                                <span class="deleted-badge">Deleted {{ $patient->deleted_at?->diffForHumans() }}</span>
                            </div>
                            <ul>
                                <li>Category: {{ ucfirst($patient->category ?? 'unknown') }}</li>
                                <li>Visits: {{ $patient->clinicVisits->count() }}</li>
                                <li>Appointments: {{ $patient->appointments->count() }}</li>
                            </ul>
                            <div class="action-row">
                                <form method="POST" action="{{ route('recently-deleted.restore.patient', $patient->id) }}">
                                    @csrf
                                    <button type="submit" class="restore-btn"><i class="fas fa-trash-restore"></i> Restore</button>
                                </form>
                                <form method="POST" action="{{ route('recently-deleted.force-delete.patient', $patient->id) }}" onsubmit="return confirm('Permanently delete this patient and all linked deleted records?');">
                                    @csrf
                                    <button type="submit" class="delete-btn"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="deleted-section">
            <h2>Clinic Visits</h2>
            @if ($deletedVisits->isEmpty())
                <div class="section-empty">No deleted clinic visits.</div>
            @else
                <div class="records-grid">
                    @foreach ($deletedVisits as $visit)
                        <div class="deleted-card">
                            <div class="deleted-card-header">
                                <div>
                                    <strong>{{ $visit->patient?->name ?? 'Unknown patient' }}</strong>
                                    <small>{{ $visit->visit_date?->format('M d, Y') ?? 'No date' }}</small>
                                </div>
                                <span class="deleted-badge">Deleted {{ $visit->deleted_at?->diffForHumans() }}</span>
                            </div>
                            <p>{{ Str::limit($visit->diagnosis ?: $visit->complaints ?: 'No details recorded', 120) }}</p>
                            <div class="action-row">
                                <form method="POST" action="{{ route('recently-deleted.restore.clinic-visit', $visit->id) }}">
                                    @csrf
                                    <button type="submit" class="restore-btn"><i class="fas fa-trash-restore"></i> Restore</button>
                                </form>
                                <form method="POST" action="{{ route('recently-deleted.force-delete.clinic-visit', $visit->id) }}" onsubmit="return confirm('Permanently delete this clinic visit?');">
                                    @csrf
                                    <button type="submit" class="delete-btn"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="deleted-section">
            <h2>Appointments</h2>
            @if ($deletedAppointments->isEmpty())
                <div class="section-empty">No deleted appointments.</div>
            @else
                <div class="records-grid">
                    @foreach ($deletedAppointments as $appointment)
                        <div class="deleted-card">
                            <div class="deleted-card-header">
                                <div>
                                    <strong>{{ $appointment->patient?->name ?? 'Unknown patient' }}</strong>
                                    <small>{{ $appointment->appointment_date?->format('M d, Y') ?? 'No date' }}</small>
                                </div>
                                <span class="deleted-badge">Deleted {{ $appointment->deleted_at?->diffForHumans() }}</span>
                            </div>
                            <p>{{ Str::limit($appointment->reason ?: 'No reason provided', 120) }}</p>
                            <div class="action-row">
                                <form method="POST" action="{{ route('recently-deleted.restore.appointment', $appointment->id) }}">
                                    @csrf
                                    <button type="submit" class="restore-btn"><i class="fas fa-trash-restore"></i> Restore</button>
                                </form>
                                <form method="POST" action="{{ route('recently-deleted.force-delete.appointment', $appointment->id) }}" onsubmit="return confirm('Permanently delete this appointment?');">
                                    @csrf
                                    <button type="submit" class="delete-btn"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>

<style>
    .recently-deleted-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
        padding: 8px 0;
    }
    .page-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .page-title {
        margin: 0;
        font-size: 28px;
        color: var(--text-heading);
    }
    .page-subtitle {
        margin: 6px 0 0;
        font-size: 13px;
        color: var(--text-muted);
    }
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }
    .alert-success {
        background: rgba(39, 174, 96, 0.1);
        color: #1e8449;
        border: 1px solid rgba(39, 174, 96, 0.25);
    }
    .deleted-section {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 12px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .deleted-section h2 {
        margin: 0;
        font-size: 18px;
        color: var(--text-heading);
    }
    .records-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 16px;
    }
    .deleted-card {
        background: var(--bg-input);
        border: 1px solid var(--border-card);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .deleted-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }
    .deleted-card-header strong {
        display: block;
        color: var(--text-heading);
    }
    .deleted-card-header small {
        color: var(--text-muted);
    }
    .deleted-badge {
        background: rgba(231, 76, 60, 0.1);
        color: var(--text-danger);
        border: 1px solid rgba(231, 76, 60, 0.2);
        border-radius: 999px;
        padding: 4px 8px;
        font-size: 10px;
        white-space: nowrap;
    }
    .deleted-card ul {
        margin: 0;
        padding-left: 18px;
        color: var(--text-body);
        font-size: 12px;
    }
    .deleted-card p {
        margin: 0;
        color: var(--text-body);
        font-size: 13px;
    }
    .bulk-actions-bar {
        display: flex;
        justify-content: flex-end;
    }
    .bulk-clear-btn,
    .restore-btn,
    .delete-btn {
        appearance: none;
        border: none;
        border-radius: 8px;
        padding: 9px 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .bulk-clear-btn {
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        color: white;
    }
    .restore-btn {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }
    .delete-btn {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    .action-row {
        margin-top: auto;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .section-empty, .empty-state-box {
        background: var(--bg-input);
        border: 1px dashed var(--border-card);
        color: var(--text-muted);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
    }
    .empty-state-box i {
        font-size: 36px;
        margin-bottom: 12px;
        display: block;
    }
</style>
@endsection
