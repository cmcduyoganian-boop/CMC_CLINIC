<div class="patient-dashboard">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">My Health Records</h1>
            <p class="page-subtitle">Welcome, {{ auth()->user()->name }}</p>
        </div>
    </div>

    @if(!$patient)
        <!-- No Patient Record -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Welcome!</strong><br>
                You don't have a patient record yet. Visit the clinic to create one.
            </div>
        </div>
    @else
        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon visits">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div class="stat-body">
                    <h3>Total Visits</h3>
                    <p class="stat-number">{{ $totalVisits }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon upcoming">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-body">
                    <h3>Upcoming Appointments</h3>
                    <p class="stat-number">{{ $upcomingAppointments }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="stat-body">
                    <h3>Category</h3>
                    <p class="category-label">
                        <span class="badge {{ $patient->getCategoryBadgeClass() }}">
                            {{ $patient->getCategoryLabel() }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="dashboard-grid">
            <!-- Personal Information -->
            <div class="card-section info-section">
                <div class="card-header">
                    <h2 class="card-title">Personal Information</h2>
                    <a href="{{ route('settings.index') }}" class="edit-link">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <label>Name</label>
                        <p>{{ $patient->name }}</p>
                    </div>
                    <div class="info-item">
                        <label>Age</label>
                        <p>{{ $patient->age ?? '-' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Program</label>
                        <p>{{ $patient->program ?: '-' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Year/Section</label>
                        <p>{{ $patient->year_section ?? '-' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Phone</label>
                        <p>{{ $patient->phone ?? '-' }}</p>
                    </div>
                    <div class="info-item">
                        <label>Address</label>
                        <p>{{ $patient->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Last Visit Summary -->
            @if($lastVisit)
                <div class="card-section last-visit-section">
                    <div class="card-header">
                        <h2 class="card-title">Last Visit</h2>
                        <span class="visit-date">{{ $lastVisit->visit_date->format('M d, Y') }}</span>
                    </div>

                    <div class="visit-summary">
                        <div class="summary-item">
                            <span class="label">Diagnosis:</span>
                            <span class="value">{{ $lastVisit->diagnosis ?? 'Not recorded' }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Management:</span>
                            <span class="value">{{ $lastVisit->management ?? 'Not recorded' }}</span>
                        </div>

                        <div class="vitals-grid">
                            <div class="vital">
                                <span class="vital-label">Temperature</span>
                                <span class="vital-value">{{ $lastVisit->temperature ? $lastVisit->temperature . '°C' : '-' }}</span>
                            </div>
                            <div class="vital">
                                <span class="vital-label">BP</span>
                                <span class="vital-value">{{ $lastVisit->bp_systolic && $lastVisit->bp_diastolic ? $lastVisit->bp_systolic . '/' . $lastVisit->bp_diastolic : '-' }}</span>
                            </div>
                            <div class="vital">
                                <span class="vital-label">Pulse Rate</span>
                                <span class="vital-value">{{ $lastVisit->pulse_rate ? $lastVisit->pulse_rate . ' bpm' : '-' }}</span>
                            </div>
                            <div class="vital">
                                <span class="vital-label">SpO2</span>
                                <span class="vital-value">{{ $lastVisit->spo2 ? $lastVisit->spo2 . '%' : '-' }}</span>
                            </div>
                        </div>

                        @if($lastVisit->notes)
                            <div class="notes-section">
                                <strong>Notes:</strong>
                                <p>{{ $lastVisit->notes }}</p>
                            </div>
                        @endif

                        <a href="{{ route('clinic-visit.show', $lastVisit->id) }}" class="view-full-link">
                            View Full Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Clinic Visits History -->
        <div class="card-section">
            <div class="card-header">
                <h2 class="card-title">Clinic Visit History</h2>
                <a href="{{ route('patients.show', $patient->id) }}" class="view-all">View All</a>
            </div>

            @if($visits->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>No clinic visits yet</p>
                </div>
            @else
                <div class="visits-timeline">
                    @foreach($visits as $visit)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="visit-header">
                                    <strong class="visit-date">{{ $visit->visit_date->format('M d, Y') }}</strong>
                                    <span class="visit-type">Clinic Visit</span>
                                </div>
                                <div class="visit-info">
                                    <span class="label">Diagnosis:</span>
                                    <span>{{ $visit->diagnosis ?? 'Not recorded' }}</span>
                                </div>
                                <a href="{{ route('clinic-visit.show', $visit->id) }}" class="view-link">
                                    Details <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Upcoming Appointments -->
        @if($appointments->isNotEmpty())
            <div class="card-section">
                <div class="card-header">
                    <h2 class="card-title">Upcoming Appointments</h2>
                    <a href="{{ route('appointments.create') }}" class="btn-small btn-new">
                        <i class="fas fa-plus"></i> Schedule New
                    </a>
                </div>

                <div class="appointments-list">
                    @foreach($appointments as $appointment)
                        <div class="appointment-item">
                            <div class="appointment-date">
                                <div class="date-badge">
                                    {{ $appointment->appointment_date->format('d') }}
                                </div>
                                <div class="date-text">
                                    <div class="month">{{ $appointment->appointment_date->format('M Y') }}</div>
                                    <div class="time">{{ date('h:i A', strtotime($appointment->appointment_time)) }}</div>
                                </div>
                            </div>
                            <div class="appointment-details">
                                <strong>{{ $appointment->reason ?? 'General Checkup' }}</strong>
                                <span class="status-badge {{ $appointment->status }}">
                                    {{ $appointment->getStatusLabel() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3 class="actions-title">Quick Actions</h3>
            <div class="actions-grid">
                <a href="{{ route('patients.show', $patient->id) }}" class="action-card">
                    <i class="fas fa-history"></i>
                    <span>Medical History</span>
                </a>
                <a href="{{ route('appointments.create') }}" class="action-card">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Schedule Appointment</span>
                </a>
                <a href="{{ route('settings.index') }}" class="action-card">
                    <i class="fas fa-user-cog"></i>
                    <span>Account Settings</span>
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="action-card logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sign Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    @endif

    <style>
        .patient-dashboard {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #2d3e50;
        }

        .page-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: #95a5a6;
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            display: flex;
            gap: 12px;
            font-size: 13px;
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            color: #0066cc;
        }

        .alert i {
            font-size: 18px;
            flex-shrink: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .stat-icon.visits {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .stat-icon.upcoming {
            background: linear-gradient(135deg, #27ae60, #229954);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
        }

        .stat-body h3 {
            margin: 0;
            font-size: 12px;
            font-weight: 600;
            color: #95a5a6;
            text-transform: uppercase;
        }

        .stat-number {
            margin: 8px 0 0 0;
            font-size: 28px;
            font-weight: 700;
            color: #2d3e50;
        }

        .category-label {
            margin: 8px 0 0 0;
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

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .card-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            border-bottom: 2px solid #e8ecf1;
            padding-bottom: 12px;
        }

        .card-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3e50;
        }

        .edit-link,
        .view-all {
            font-size: 12px;
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .edit-link:hover,
        .view-all:hover {
            color: #2980b9;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-item label {
            font-size: 11px;
            font-weight: 700;
            color: #95a5a6;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .info-item p {
            margin: 0;
            font-size: 13px;
            color: #2d3e50;
            font-weight: 600;
        }

        .info-section {
            grid-column: 1;
        }

        .last-visit-section {
            grid-column: 2;
        }

        .visit-date {
            font-size: 12px;
            color: #95a5a6;
        }

        .visit-summary {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .summary-item {
            display: flex;
            flex-direction: column;
        }

        .summary-item .label {
            font-size: 10px;
            font-weight: 700;
            color: #95a5a6;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .summary-item .value {
            font-size: 13px;
            color: #2d3e50;
            font-weight: 600;
        }

        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 6px;
            margin: 8px 0;
        }

        .vital {
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .vital-label {
            font-size: 10px;
            color: #95a5a6;
            font-weight: 600;
        }

        .vital-value {
            font-size: 12px;
            color: #2d3e50;
            font-weight: 700;
            margin-top: 4px;
        }

        .notes-section {
            padding: 12px;
            background: #f0f8ff;
            border-radius: 6px;
            border-left: 3px solid #3498db;
        }

        .notes-section strong {
            font-size: 11px;
            text-transform: uppercase;
            color: #3498db;
        }

        .notes-section p {
            margin: 6px 0 0 0;
            font-size: 12px;
            color: #2d3e50;
            line-height: 1.5;
        }

        .view-full-link {
            color: #3498db;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            transition: all 0.2s;
        }

        .view-full-link:hover {
            color: #2980b9;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #95a5a6;
        }

        .empty-state i {
            font-size: 36px;
            opacity: 0.3;
            display: block;
            margin-bottom: 12px;
        }

        .empty-state p {
            margin: 0;
            font-size: 13px;
        }

        .visits-timeline {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .timeline-item {
            display: flex;
            gap: 16px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .timeline-item:hover {
            background: #f0f8ff;
        }

        .timeline-marker {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            flex-shrink: 0;
        }

        .timeline-content {
            flex: 1;
        }

        .visit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .visit-header strong {
            font-size: 12px;
            color: #2d3e50;
        }

        .visit-type {
            font-size: 10px;
            background: #dbeafe;
            color: #1d4ed8;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }

        .visit-info {
            font-size: 11px;
            color: #95a5a6;
            margin-bottom: 6px;
        }

        .visit-info span {
            color: #2d3e50;
            font-weight: 600;
        }

        .view-link {
            color: #3498db;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }

        .view-link:hover {
            color: #2980b9;
        }

        .appointments-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .appointment-item {
            display: flex;
            gap: 16px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #27ae60;
            transition: all 0.2s;
        }

        .appointment-item:hover {
            background: #f0f8ff;
        }

        .appointment-date {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-badge {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .date-text {
            display: flex;
            flex-direction: column;
        }

        .month {
            font-size: 10px;
            color: #95a5a6;
            font-weight: 600;
        }

        .time {
            font-size: 11px;
            color: #2d3e50;
            font-weight: 700;
        }

        .appointment-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .appointment-details strong {
            font-size: 12px;
            color: #2d3e50;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            width: fit-content;
        }

        .status-badge.scheduled {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-badge.completed {
            background: #d5f4e6;
            color: #27ae60;
        }

        .quick-actions {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .actions-title {
            margin: 0 0 16px 0;
            font-size: 14px;
            font-weight: 700;
            color: #2d3e50;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
        }

        .action-card {
            background: linear-gradient(135deg, #f9fafb, #ecf0f1);
            border: 1px solid #e8ecf1;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            text-decoration: none;
            color: #2d3e50;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .action-card i {
            font-size: 24px;
            color: #3498db;
        }

        .action-card span {
            font-size: 12px;
            font-weight: 600;
        }

        .action-card:hover {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-color: #3498db;
            transform: translateY(-2px);
        }

        .action-card:hover i {
            color: white;
        }

        .action-card.logout:hover {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border-color: #e74c3c;
        }

        .btn-small {
            border: none;
            background: #3498db;
            color: white;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        .btn-small:hover {
            background: #2980b9;
        }

        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .info-section {
                grid-column: 1;
            }

            .last-visit-section {
                grid-column: 1;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .vitals-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page-title {
                font-size: 22px;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .appointment-date {
                flex-direction: column;
                align-items: flex-start;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</div>