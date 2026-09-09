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

        /* ── Search bar ───────────────────────────── */
        .reports-search {
            display: flex;
            align-items: center;
            width: min(420px, 100%);
            min-height: 46px;
            padding: 0 16px;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            gap: 10px;
        }

        .reports-search .search-icon {
            position: static;
            transform: none;
            flex-shrink: 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .reports-search .search-input {
            flex: 1;
            min-width: 0;
            border: 0;
            outline: 0;
            padding: 10px 0;
            background: transparent;
            color: var(--text-heading);
            font-size: 13px;
            font-family: 'Figtree', sans-serif;
        }

        /* ── Stat cards (top row) ─────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 14px;
            padding: 20px 22px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.22s;
        }

        .stat-card:hover {
            box-shadow: 0 8px 22px rgba(0,0,0,0.13);
            transform: translateY(-3px);
            border-color: rgba(41,128,185,0.25);
        }

        .stat-card .icon {
            font-size: 22px;
            color: #2980b9;
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, rgba(41,128,185,0.15), rgba(26,110,168,0.08));
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid rgba(41,128,185,0.18);
        }

        .stat-body { flex: 1; min-width: 0; }

        .stat-body h3 {
            margin: 0 0 6px;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .stat-number {
            margin: 0;
            font-size: 34px;
            font-weight: 800;
            color: var(--text-heading);
            line-height: 1;
        }

        /* ── Report Cards Grid ────────────────────── */
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .report-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            display: flex;
            flex-direction: column;
            gap: 14px;
            transition: all 0.22s;
        }

        .report-card:hover {
            box-shadow: 0 10px 28px rgba(0,0,0,0.13);
            transform: translateY(-4px);
            border-color: rgba(41,128,185,0.3);
        }

        .report-header {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .report-icon {
            font-size: 19px;
            width: 46px;
            height: 46px;
            background: linear-gradient(135deg, #2980b9, #1a6ea8);
            color: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(41,128,185,0.35);
        }

        .report-header h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
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