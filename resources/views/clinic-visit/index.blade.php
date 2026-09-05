<x-app-with-sidebar>
    <x-slot name="header">Clinic Visits</x-slot>

    <div class="clinic-visit-list-page">
        <div class="table-toolbar">
            <form class="records-search" method="GET" action="{{ route('clinic-visit.index') }}" role="search">
                <i class="fas fa-search records-search-icon" aria-hidden="true"></i>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search clinic records..." aria-label="Search clinic records" oninput="clearTimeout(this.searchTimer); this.searchTimer = setTimeout(() => this.form.requestSubmit(), 350)">
            </form>
            <a href="{{ route('clinic-visit.create') }}" class="btn-new-visit">
                <i class="fas fa-plus"></i> New Visit
            </a>
        </div>

        <!-- Clinic Visits Table -->
        <div class="table-container">
            <table class="visits-table">
                <colgroup>
                    <col class="col-date">
                    <col class="col-name">
                    <col class="col-email">
                    <col class="col-yr">
                    <col class="col-age">
                    <col class="col-vital"><!-- T° -->
                    <col class="col-vital"><!-- PR -->
                    <col class="col-vital"><!-- RR -->
                    <col class="col-vital"><!-- BP -->
                    <col class="col-vital"><!-- HT -->
                    <col class="col-vital"><!-- WT -->
                    <col class="col-vital"><!-- BMI -->
                    <col class="col-vital"><!-- SpO2 -->
                    <col class="col-status"><!-- VS Status -->
                    <col class="col-comp">
                    <col class="col-diag">
                    <col class="col-mgmt">
                    <col class="col-addr">
                    <col class="col-sex">
                    <col class="col-act">
                </colgroup>
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>FULL NAME</th>
                        <th>EMAIL</th>
                        <th>YEAR &amp; SECTION</th>
                        <th class="cell-center">AGE</th>
                        <th colspan="8">VITAL SIGNS</th>
                        <th class="cell-center">VS STATUS</th>
                        <th>COMPLAINTS</th>
                        <th>DIAGNOSIS</th>
                        <th>MANAGEMENT</th>
                        <th>ADDRESS</th>
                        <th class="cell-center">SEX</th>
                        <th class="cell-center">ACTIONS</th>
                    </tr>
                    <tr class="vital-signs-header">
                        <th colspan="5"></th>
                        <th>T°</th>
                        <th>PR</th>
                        <th>RR</th>
                        <th>BP</th>
                        <th>HT</th>
                        <th>WT</th>
                        <th>BMI</th>
                        <th>SpO2</th>
                        <th></th><!-- VS Status sub-col -->
                        <th colspan="6"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $visit)
                        @php
                            $vsAssessment = $visit->getVitalSignsAssessment();
                            $vsOverall    = $vsAssessment['overall'];
                        @endphp
                        <tr class="visit-row">
                            <td>{{ $visit->visit_date->format('m/d/Y') }}</td>
                            <td class="patient-name">{{ $visit->patient->name ?? 'N/A' }}</td>
                            <td class="text-small">{{ $visit->patient->email ?? '-' }}</td>
                            <td class="year-section">{{ $visit->patient->year_section ?? 'N/A' }}</td>
                            <td class="vital-sign">{{ $visit->patient->age ?? 'N/A' }}</td>
                            <td class="vital-sign">{{ $visit->temperature ? $visit->temperature . '°C' : '-' }}</td>
                            <td class="vital-sign">{{ $visit->pulse_rate ?: '-' }}</td>
                            <td class="vital-sign">{{ $visit->respiratory_rate ?: '-' }}</td>
                            <td class="vital-sign">{{ $visit->bp_systolic && $visit->bp_diastolic ? $visit->bp_systolic . '/' . $visit->bp_diastolic : '-' }}</td>
                            <td class="vital-sign">{{ $visit->height ? $visit->height . 'cm' : '-' }}</td>
                            <td class="vital-sign">{{ $visit->weight ? $visit->weight . 'kg' : '-' }}</td>
                            <td class="vital-sign">{{ $visit->getBMI() ?: '-' }}</td>
                            <td class="vital-sign">{{ $visit->spo2 ? $visit->spo2 . '%' : '-' }}</td>
                            <td class="cell-center">
                                @if ($vsOverall)
                                    <span class="idx-vs-badge idx-vs-{{ $vsOverall }}" title="{{ \App\Support\VitalSigns::label($vsOverall) }}">
                                        <span class="idx-vs-label">{{ \App\Support\VitalSigns::label($vsOverall) }}</span>
                                    </span>
                                @else
                                    <span class="idx-vs-badge idx-vs-na">—</span>
                                @endif
                            </td>
                            <td class="text-small">{{ $visit->complaints ?: '-' }}</td>
                            <td class="text-small">{{ $visit->diagnosis ?: '-' }}</td>
                            <td class="text-small">{{ $visit->management ?: '-' }}</td>
                            <td class="text-small">{{ $visit->address ?: '-' }}</td>
                            <td class="vital-sign">{{ ucfirst($visit->sex ?: '-') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('clinic-visit.show', $visit->id) }}" class="btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clinic-visit.edit', $visit->id) }}" class="btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('clinic-visit.destroy', $visit->id) }}" style="display:inline;" onsubmit="return confirm('Delete this visit record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" style="text-align:center;padding:40px;color:var(--text-muted);">
                                No clinic visits recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($visits->hasPages())
            <div class="pagination-section">
                <p class="pagination-info">
                    Showing {{ $visits->firstItem() ?? 0 }} to {{ $visits->lastItem() ?? 0 }} of {{ $visits->total() }} visits
                </p>
                <div class="pagination">
                    {{ $visits->links() }}
                </div>
            </div>
        @endif
    </div>

    <style>
        /* ── Page layout ────────────────────────────────────── */
        .clinic-visit-list-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .table-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .records-search {
            display: flex;
            align-items: center;
            width: min(520px, 100%);
            height: 42px;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .records-search:focus-within {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.12);
        }

        .records-search-icon {
            padding-left: 13px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .records-search input {
            flex: 1;
            min-width: 0;
            height: 100%;
            padding: 0 10px;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--text-heading);
            font: inherit;
            font-size: 12px;
        }

        .records-search input::placeholder {
            color: var(--text-muted);
        }

        /* ── New Visit button ───────────────────────────────── */
        .btn-new-visit {
            background: linear-gradient(135deg, #27ae60, #1e8449);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(39,174,96,.25);
            white-space: nowrap;
        }

        .btn-new-visit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(39,174,96,.35);
        }

        /* ── Table container ────────────────────────────────── */
        .table-container {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        /* ── Table base ─────────────────────────────────────── */
        .visits-table {
            width: 100%;
            min-width: 1180px;       /* keeps all record fields readable on narrow screens */
            border-collapse: collapse;
            font-size: 12px;
            table-layout: fixed;     /* column widths are honoured */
        }

        /* ── Column widths (fixed layout) ───────────────────── */
        .visits-table col.col-date    { width: 74px;  }
        .visits-table col.col-name    { width: 118px; }
        .visits-table col.col-email   { width: 132px; }
        .visits-table col.col-yr      { width: 78px;  }
        .visits-table col.col-age     { width: 46px;  }
        .visits-table col.col-vital   { width: 48px;  }  /* x8 = 384px */
            .visits-table col.col-status  { width: 120px; }
        .visits-table col.col-comp    { width: 94px; }
        .visits-table col.col-diag    { width: 82px; }
        .visits-table col.col-mgmt    { width: 82px; }
        .visits-table col.col-addr    { width: 96px; }
        .visits-table col.col-sex     { width: 42px;  }
        .visits-table col.col-act     { width: 96px;  }

        /* ── VS Status badges (index table) ─────────────────── */
        .idx-vs-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 3px 7px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
            line-height: 1.3;
        }
        .idx-vs-label { font-size: 9px; white-space: nowrap; }
        .idx-vs-normal       { background: rgba(39,174,96,0.15); color: #27ae60; }
        .idx-vs-above_normal { background: rgba(243,156,18,0.15); color: #b87e00; }
        .idx-vs-below_normal { background: rgba(52,152,219,0.15); color: #2980b9; }
        .idx-vs-abnormal     { background: rgba(231,76,60,0.18); color: #e74c3c; font-weight: 800; }
        .idx-vs-na           { background: transparent; color: var(--text-muted); }

        /* ── Primary header row (blue gradient) ─────────────── */
        .visits-table thead tr:first-child th {
            padding: 13px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            white-space: normal;
            line-height: 1.2;
            color: #fff;
            background: linear-gradient(135deg, #2980b9 0%, #1a6ea8 100%);
            border-bottom: 2px solid rgba(255,255,255,0.15);
            border-right: 1px solid rgba(255,255,255,0.12);
        }

        .visits-table thead tr:first-child th:last-child {
            border-right: none;
        }

        /* ── Vital-signs sub-header row ─────────────────────── */
        /* Uses theme variables so it is readable in BOTH modes  */
        .vital-signs-header th {
            padding: 8px 6px !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            text-align: center !important;
            letter-spacing: 0.3px;
            text-transform: uppercase;

            /* Theme-aware: visible in dark AND light mode */
            background: var(--bg-input) !important;
            color: var(--text-heading) !important;
            border-bottom: 2px solid var(--border-inner) !important;
            border-right: 1px solid var(--border-inner) !important;
        }

        .vital-signs-header th:last-child {
            border-right: none !important;
        }

        /* ── Body rows ──────────────────────────────────────── */
        .visits-table tbody tr {
            border-bottom: 1px solid var(--border-inner);
            transition: background 0.15s;
        }

        .visits-table tbody tr:last-child {
            border-bottom: none;
        }

        .visits-table tbody tr:hover {
            background: var(--bg-input);
        }

        .visits-table td {
            padding: 9px 6px;
            color: var(--text-heading);
            border-right: 1px solid var(--border-inner);
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: top;
            font-size: 11px;
            line-height: 1.4;
        }

        .visits-table td:last-child { border-right: none; }

        /* ── Cell variants ──────────────────────────────────── */
        .patient-name {
            font-weight: 700;
            color: #3498db;
            word-break: break-word;
        }

        .year-section {
            font-weight: 600;
            color: var(--text-heading);
            font-size: 12px;
        }

        .cell-center {
            text-align: center;
        }

        .vital-sign {
            text-align: center;
            font-weight: 600;
            color: var(--text-heading);
            font-size: 11px;
            padding: 11px 4px !important;
        }

        .text-small {
            font-size: 11px;
            color: var(--text-heading);   /* was text-muted → too faint in light */
            word-break: break-word;
            white-space: normal;
            line-height: 1.45;
        }

        /* ── Action buttons ─────────────────────────────────── */
        .action-buttons {
            display: flex;
            gap: 6px;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
            padding: 0 4px;
        }

        .btn-view,
        .btn-edit,
        .btn-delete {
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 6px 7px;
            border-radius: 6px;
            transition: all 0.18s;
            font-size: 13px;
            text-decoration: none;
            line-height: 1;
        }

        .btn-view  { color: #3498db; }
        .btn-edit  { color: #f39c12; }
        .btn-delete{ color: #e74c3c; }

        .btn-view:hover  { background: rgba(52,152,219,.12); }
        .btn-edit:hover  { background: rgba(243,156,18,.12); }
        .btn-delete:hover{ background: rgba(231,76,60,.12); }

        /* ── Pagination ─────────────────────────────────────── */
        .pagination-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 10px;
            padding: 14px 20px;
        }

        .pagination-info {
            margin: 0;
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .pagination { display: flex; gap: 6px; }

        .pagination a,
        .pagination span {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid var(--border-card);
            background: var(--bg-card);
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s;
        }

        .pagination a:hover       { background:#3498db; color:#fff; border-color:#3498db; }
        .pagination .active       { background:#3498db; color:#fff; border-color:#3498db; }
        .pagination .disabled     { opacity:.45; cursor:not-allowed; }

        /* ── Responsive ─────────────────────────────────────── */
        @media (max-width: 768px) {
            .table-toolbar { flex-direction: column; align-items: stretch; }
            .records-search, .btn-new-visit { width: 100%; }
            .btn-new-visit { justify-content: center; }
            .pagination-section { flex-direction: column; gap: 10px; }
            .pagination { width: 100%; justify-content: center; }
        }
    </style>
</x-app-with-sidebar>
