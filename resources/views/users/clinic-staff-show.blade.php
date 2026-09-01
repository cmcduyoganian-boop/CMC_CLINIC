<x-app-with-sidebar>
    <x-slot name="header">Clinic Staff Details</x-slot>

    <div class="clinic-staff-show-page">
        <!-- Back Button -->
        <a href="{{ route('clinic-staff.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Staff List
        </a>

        <!-- Staff Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">{{ substr($staff->name, 0, 1) }}</div>
                <div class="profile-info">
                    <h1 class="profile-name">{{ $staff->name }}</h1>
                    <p class="profile-role">{{ $staff->getRoleLabel() }}</p>
                    <div class="profile-badges">
                        <span class="badge {{ $staff->getApprovalStatusBadgeClass() }}">
                            {{ $staff->getApprovalStatusLabel() }}
                        </span>
                        <span class="badge {{ $staff->getActiveStatusBadgeClass() }}">
                            {{ $staff->getActiveStatusLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="profile-stats">
                <div class="p-stat">
                    <h3>Total Patients</h3>
                    <p class="p-stat-value">{{ $stats['total_patients'] }}</p>
                </div>
                <div class="p-stat">
                    <h3>Total Visits</h3>
                    <p class="p-stat-value">{{ $stats['total_visits'] }}</p>
                </div>
                <div class="p-stat">
                    <h3>Last Login</h3>
                    <p class="p-stat-value">
                        @if($stats['last_login'])
                            {{ $stats['last_login']->format('M d, Y H:i A') }}
                        @else
                            Never
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Patients Section -->
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-users"></i> Patients Recorded
                </h2>
            </div>

            @if($patients->count() > 0)
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Category</th>
                                <th>Year/Section</th>
                                <th>Visits</th>
                                <th>Last Visit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $patient)
                                <tr>
                                    <td>{{ $patient->name }}</td>
                                    <td>
                                        <span class="badge {{ $patient->getCategoryBadgeClass() }}">
                                            {{ $patient->getCategoryLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $patient->year_section ?? 'N/A' }}</td>
                                    <td>{{ $patient->clinicVisits->count() }}</td>
                                    <td>
                                        @if($patient->clinicVisits->first())
                                            {{ $patient->clinicVisits->first()->visit_date->format('M d, Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('patients.show', $patient->id) }}" class="btn-sm" title="View Patient">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination">
                    {{ $patients->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>No patients recorded by this staff yet.</p>
                </div>
            @endif
        </div>

        <!-- Activity Section -->
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-history"></i> Login Activity
                </h2>
            </div>

            @if($activities->count() > 0)
                <div class="activity-list">
                    @foreach ($activities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="activity-details">
                                <p class="activity-action">
                                    Logged in
                                    @if($activity->ip_address)
                                        from <span class="activity-ip">{{ $activity->ip_address }}</span>
                                    @endif
                                </p>
                                <p class="activity-time">
                                    {{ $activity->logged_in_at->format('F d, Y H:i A') }}
                                </p>
                                @if($activity->logged_out_at)
                                    <p class="activity-logout">
                                        Logged out: {{ $activity->logged_out_at->format('F d, Y H:i A') }}
                                    </p>
                                @endif
                            </div>
                            <div class="activity-duration">
                                @if($activity->logged_out_at)
                                    @php
                                        $duration = $activity->logged_in_at->diffInMinutes($activity->logged_out_at);
                                        $hours = floor($duration / 60);
                                        $minutes = $duration % 60;
                                    @endphp
                                    <span class="duration-badge">
                                        {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes }}m
                                    </span>
                                @else
                                    <span class="duration-badge active">Active</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="table-pagination">
                    {{ $activities->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <p>No activity records found.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .clinic-staff-show-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-input);
            color: var(--text-muted);
            border: 1px solid var(--border-input);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            width: fit-content;
        }

        .btn-back:hover {
            background: var(--border-input);
        }

        .profile-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-inner);
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .profile-role {
            margin: 4px 0 8px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .profile-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .p-stat {
            background: var(--bg-input);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }

        .p-stat h3 {
            margin: 0;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .p-stat-value {
            margin: 8px 0 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .section-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border-inner);
        }

        .section-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #3498db;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .data-table thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            background: var(--bg-input);
            border-bottom: 1px solid var(--border-inner);
        }

        .data-table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-inner);
            color: var(--text-body);
        }

        .data-table tbody tr:hover {
            background: var(--bg-input);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-success {
            background: #d5f4e6;
            color: #27ae60;
        }

        .badge-danger {
            background: #fadbd8;
            color: #e74c3c;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
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

        .btn-sm {
            background: #3498db;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-sm:hover {
            background: #2980b9;
        }

        .table-pagination {
            padding: 16px 0 0;
            display: flex;
            justify-content: flex-end;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: var(--bg-input);
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dbeafe;
            color: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .activity-details {
            flex: 1;
        }

        .activity-action {
            margin: 0;
            font-size: 13px;
            color: var(--text-heading);
            font-weight: 600;
        }

        .activity-ip {
            color: #3498db;
            font-family: monospace;
        }

        .activity-time {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--text-muted);
        }

        .activity-logout {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--text-muted);
        }

        .activity-duration {
            flex-shrink: 0;
        }

        .duration-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: #d5f4e6;
            color: #27ae60;
        }

        .duration-badge.active {
            background: #fff3cd;
            color: #856404;
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 48px;
            opacity: 0.3;
            display: block;
            margin-bottom: 12px;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-badges {
                justify-content: center;
            }

            .activity-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</x-app-with-sidebar>
