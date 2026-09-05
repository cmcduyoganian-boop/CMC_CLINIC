<x-app-with-sidebar>
    <x-slot name="header">Reports & Analytics</x-slot>

    <div class="reports-page">
        <div class="reports-search search-section">
            <i class="fas fa-search search-icon" aria-hidden="true"></i>
            <input type="search" class="search-input" placeholder="Search reports and analytics..." aria-label="Search reports and analytics">
        </div>

        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users icon"></i>
                <div class="stat-body">
                    <h3>Total Patients</h3>
                    <p class="stat-number">{{ \App\Models\Patient::count() }}</p>
                </div>
            </div>

            <div class="stat-card">
                <i class="fas fa-stethoscope icon"></i>
                <div class="stat-body">
                    <h3>Clinic Visits</h3>
                    <p class="stat-number">{{ \App\Models\ClinicVisit::count() }}</p>
                </div>
            </div>

            <div class="stat-card">
                <i class="fas fa-calendar-check icon"></i>
                <div class="stat-body">
                    <h3>Completed Appointments</h3>
                    <p class="stat-number">{{ \App\Models\Appointment::where('status', 'completed')->count() }}</p>
                </div>
            </div>

            <div class="stat-card">
                <i class="fas fa-pills icon"></i>
                <div class="stat-body">
                    <h3>Medicine Items</h3>
                    <p class="stat-number">{{ \App\Models\Medicine::count() }}</p>
                </div>
            </div>
        </div>

        <!-- Report Cards -->
        <div class="reports-grid">
            <!-- Patients Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-users report-icon"></i>
                    <h3>Patient Reports</h3>
                </div>
                <p class="report-description">Patient demographics, categories, and distribution</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong>{{ \App\Models\Patient::where('category', 'student')->count() }}</strong>
                        <small>Students</small>
                    </span>
                    <span class="stat-item">
                        <strong>{{ \App\Models\Patient::where('category', 'faculty')->count() }}</strong>
                        <small>Faculty</small>
                    </span>
                    <span class="stat-item">
                        <strong>{{ \App\Models\Patient::where('category', 'staff')->count() }}</strong>
                        <small>Staff</small>
                    </span>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.patients') }}" class="btn-view-report">
                        View Report <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('reports.download', 'patients') }}" class="btn-download-report">
                        <i class="fas fa-download"></i> Excel
                    </a>
                </div>
            </div>

            <!-- Clinic Visits Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-stethoscope report-icon"></i>
                    <h3>Clinic Visits</h3>
                </div>
                <p class="report-description">Visit statistics, trends, and patterns</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong>{{ \App\Models\ClinicVisit::count() }}</strong>
                        <small>Total Visits</small>
                    </span>
                    <span class="stat-item">
                        <strong>{{ \App\Models\ClinicVisit::whereDate('visit_date', \Carbon\Carbon::today())->count() }}</strong>
                        <small>Today</small>
                    </span>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.clinic-visits') }}" class="btn-view-report">
                        View Report <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('reports.download', 'clinic-visits') }}" class="btn-download-report">
                        <i class="fas fa-download"></i> Excel
                    </a>
                </div>
            </div>

            <!-- Diagnosis Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-heartbeat report-icon"></i>
                    <h3>Diagnosis Report</h3>
                </div>
                <p class="report-description">Common diagnoses and health patterns</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong>{{ \App\Models\ClinicVisit::whereNotNull('diagnosis')->distinct('diagnosis')->count() }}</strong>
                        <small>Unique Diagnoses</small>
                    </span>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.diagnosis') }}" class="btn-view-report">
                        View Report <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('reports.download', 'diagnosis') }}" class="btn-download-report">
                        <i class="fas fa-download"></i> Excel
                    </a>
                </div>
            </div>

            <!-- Medicines Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-pills report-icon"></i>
                    <h3>Medicine Inventory</h3>
                </div>
                <p class="report-description">Stock levels, usage, and inventory status</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong>{{ \App\Models\Medicine::where('quantity', '<=', \App\Models\Medicine::raw('minimum_stock'))->count() }}</strong>
                        <small>Low Stock</small>
                    </span>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.medicines') }}" class="btn-view-report">
                        View Report <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('reports.download', 'medicines') }}" class="btn-download-report">
                        <i class="fas fa-download"></i> Excel
                    </a>
                </div>
            </div>

            <!-- Appointments Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-calendar-alt report-icon"></i>
                    <h3>Appointments</h3>
                </div>
                <p class="report-description">Appointment statistics and completion rates</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong>{{ \App\Models\Appointment::where('status', 'scheduled')->count() }}</strong>
                        <small>Upcoming</small>
                    </span>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.appointments') }}" class="btn-view-report">
                        View Report <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('reports.download', 'appointments') }}" class="btn-download-report">
                        <i class="fas fa-download"></i> Excel
                    </a>
                </div>
            </div>

            <!-- Vital Signs Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-thermometer-half report-icon"></i>
                    <h3>Vital Signs</h3>
                </div>
                <p class="report-description">Abnormal readings and health alerts</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong>{{ \App\Models\ClinicVisit::where('temperature', '>', 38)->count() }}</strong>
                        <small>High Fever Cases</small>
                    </span>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.vital-signs') }}" class="btn-view-report">
                        View Report <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('reports.download', 'vital-signs') }}" class="btn-download-report">
                        <i class="fas fa-download"></i> Excel
                    </a>
                </div>
            </div>

            <!-- Clinic Tally Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-clipboard-list report-icon"></i>
                    <h3>Clinic Tally Report</h3>
                </div>
                <p class="report-description">Weekly, monthly, and semestral tally of visits, demographics, complaints, medications, and services</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong>{{ \App\Models\ClinicVisit::count() }}</strong>
                        <small>Total Visits</small>
                    </span>
                </div>
                <div class="report-actions">
                    <a href="{{ route('reports.clinic-report') }}" class="btn-view-report">
                        View Report <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.reports-search input')?.addEventListener('input', function () {
            const term = this.value.trim().toLowerCase();
            document.querySelectorAll('.reports-page .stat-card, .reports-page .report-card').forEach((card) => {
                card.hidden = term !== '' && !card.textContent.toLowerCase().includes(term);
            });
        });
    </script>

    <style>
        .reports-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .reports-search {
            display: flex;
            align-items: center;
            width: min(420px, 100%);
            min-height: 42px;
            padding: 0 14px;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .reports-search .search-icon {
            position: static;
            transform: none;
            flex-shrink: 0;
            color: var(--text-muted);
        }

        .reports-search .search-input {
            width: 100%;
            min-width: 0;
            border: 0;
            outline: 0;
            padding: 9px 0 9px 10px;
            background: transparent;
            color: var(--text-heading);
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
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transform: translateY(-2px);
        }

        .stat-card .icon {
            font-size: 32px;
            color: #38bdf8;
            width: 50px;
            height: 50px;
            background: rgba(56, 189, 248, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
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

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .report-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: all 0.2s;
        }

        .report-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            transform: translateY(-4px);
        }

        .report-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .report-icon {
            font-size: 24px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #38bdf8 0%, #2563eb 100%);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .report-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .report-description {
            margin: 0;
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .report-stats {
            display: flex;
            gap: 12px;
        }

        .stat-item {
            flex: 1;
            background: var(--bg-input);
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            border-left: 3px solid #38bdf8;
        }

        .stat-item strong {
            display: block;
            font-size: 20px;
            color: #38bdf8;
            font-weight: 700;
        }

        .stat-item small {
            display: block;
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 4px;
            text-transform: uppercase;
        }

        .btn-view-report {
            background: linear-gradient(135deg, #38bdf8 0%, #2563eb 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-view-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
        }

        .report-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .btn-download-report {
            flex: 1;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-download-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }

        @media (max-width: 768px) {
            .reports-search { width: 100%; }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .reports-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-with-sidebar>