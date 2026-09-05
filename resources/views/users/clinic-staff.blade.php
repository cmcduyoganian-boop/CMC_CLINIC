<x-app-with-sidebar>
    <x-slot name="header">Clinic Staff Management</x-slot>

    <div class="clinic-staff-page">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-body">
                    <h3>Total Staff</h3>
                    <p class="stat-number">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-body">
                    <h3>Active</h3>
                    <p class="stat-number">{{ $stats['active'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon inactive">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-body">
                    <h3>Inactive</h3>
                    <p class="stat-number">{{ $stats['inactive'] }}</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <div class="chart-card">
                <h3 class="chart-title">Active vs Inactive Staff</h3>
                <div class="chart-wrapper">
                    <canvas id="staffStatusChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3 class="chart-title">Top Staff by Visits Recorded</h3>
                <div class="chart-wrapper">
                    <canvas id="topStaffChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <form method="GET" action="{{ route('clinic-staff.index') }}" class="filters-form">
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" class="filter-input" placeholder="Search by name, email, username..." value="{{ $search }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-input">
                        <option value="">All Status</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Filter
                </button>
                @if($search || $status)
                    <a href="{{ route('clinic-staff.index') }}" class="btn-reset">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Staff Table -->
        <div class="table-card">
            <table class="staff-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staff as $member)
                        <tr>
                            <td>
                                <div class="staff-name">
                                    <div class="staff-avatar">{{ substr($member->name, 0, 1) }}</div>
                                    <span>{{ $member->name }}</span>
                                </div>
                            </td>
                            <td>{{ $member->username }}</td>
                            <td>{{ $member->email }}</td>
                            <td>
                                <span class="badge {{ $member->getActiveStatusBadgeClass() }}">
                                    {{ $member->getActiveStatusLabel() }}
                                </span>
                            </td>
                            <td>
                                @if($member->lastActivity && $member->lastActivity->logged_in_at)
                                    <span title="{{ $member->lastActivity->logged_in_at->format('F d, Y H:i A') }}">
                                        {{ $member->lastActivity->logged_in_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-muted">Never logged in</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('clinic-staff.show', $member->id) }}" class="btn-view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-cell">
                                <div class="empty-state">
                                    <i class="fas fa-user-md"></i>
                                    <p>No clinic staff found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($staff->hasPages())
            <div class="pagination-wrapper">
                {{ $staff->links() }}
            </div>
        @endif
    </div>

    <style>
        .clinic-staff-page {
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
            color: var(--text-heading);
        }

        .page-description {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            flex-shrink: 0;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .stat-icon.active {
            background: linear-gradient(135deg, #27ae60, #229954);
        }

        .stat-icon.inactive {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .stat-body h3 {
            margin: 0;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .stat-number {
            margin: 8px 0 0 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .filters-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .filters-form {
            display: flex;
            gap: 16px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-heading);
            text-transform: uppercase;
        }

        .filter-input {
            border: 1px solid var(--border-input);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            background: var(--bg-input);
            color: var(--text-heading);
            width: 100%;
        }

        .filter-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn-filter {
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-filter:hover {
            background: #2980b9;
        }

        .btn-reset {
            background: var(--bg-input);
            color: var(--text-muted);
            border: 1px solid var(--border-input);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-reset:hover {
            background: var(--border-input);
        }

        .table-card {
            background: var(--bg-card);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .staff-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .staff-table thead th {
            padding: 14px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            background: var(--bg-input);
            border-bottom: 1px solid var(--border-inner);
        }

        .staff-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-inner);
            color: var(--text-body);
        }

        .staff-table tbody tr:hover {
            background: var(--bg-input);
        }

        .staff-name {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .staff-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
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

        .text-muted {
            color: var(--text-muted);
            font-size: 12px;
        }

        .btn-view {
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

        .btn-view:hover {
            background: #2980b9;
        }

        .empty-cell {
            padding: 60px 20px;
        }

        .empty-state {
            text-align: center;
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

        .pagination-wrapper {
            padding: 16px 20px;
            border-top: 1px solid var(--border-inner);
            background: var(--bg-card);
            border-radius: 0 0 10px 10px;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 12px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .filters-form {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .staff-table {
                font-size: 12px;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ============ CHARTS ============ */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .chart-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .chart-title {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .chart-wrapper {
            position: relative;
            width: 100%;
            min-height: 280px;
        }

        .chart-wrapper canvas {
            max-height: 320px;
        }
    </style>

    <script>
        (function () {
            const statusCtx = document.getElementById('staffStatusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($chartData['status']['labels']),
                        datasets: [{
                            data: @json($chartData['status']['data']),
                            backgroundColor: @json($chartData['status']['colors']),
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyleWidth: 10,
                                    font: {
                                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                                        size: 13,
                                    },
                                    color: getComputedStyle(document.body).getPropertyValue('--text-body').trim(),
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: { size: 13, weight: '700' },
                                bodyFont: { size: 12 },
                            }
                        }
                    }
                });
            }

            const topStaffCtx = document.getElementById('topStaffChart');
            if (topStaffCtx) {
                const labels = @json($chartData['topStaff']['labels']);
                const data = @json($chartData['topStaff']['data']);

                new Chart(topStaffCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Visits Recorded',
                            data: data,
                            backgroundColor: [
                                'rgba(52, 152, 219, 0.8)',
                                'rgba(39, 174, 96, 0.8)',
                                'rgba(155, 89, 182, 0.8)',
                                'rgba(241, 196, 15, 0.8)',
                                'rgba(231, 76, 60, 0.8)',
                                'rgba(52, 152, 219, 0.8)',
                                'rgba(39, 174, 96, 0.8)',
                                'rgba(155, 89, 182, 0.8)',
                            ],
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: { size: 13, weight: '700' },
                                bodyFont: { size: 12 },
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)',
                                },
                                ticks: {
                                    color: getComputedStyle(document.body).getPropertyValue('--text-muted').trim(),
                                    font: { size: 11 },
                                }
                            },
                            y: {
                                grid: { display: false },
                                ticks: {
                                    color: getComputedStyle(document.body).getPropertyValue('--text-heading').trim(),
                                    font: { size: 12, weight: '600' },
                                }
                            }
                        }
                    }
                });
            }
        })();
    </script>
</x-app-with-sidebar>
