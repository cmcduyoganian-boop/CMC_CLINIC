<div class="clinic-report-page">
        <div class="report-header">
            <div>
                <h1 class="report-title">Clinic Report</h1>
                <p class="report-subtitle">Generate weekly, monthly, or semestral tally reports</p>
            </div>
        </div>

        <div class="report-controls">
            <div class="control-group">
                <label class="control-label">Report Type</label>
                <select wire:model="reportType" class="form-control">
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="semestral">Semestral</option>
                </select>
            </div>

            <div class="control-group">
                <label class="control-label">Start Date</label>
                <input type="date" wire:model="startDate" class="form-control">
            </div>

            <div class="control-group">
                <label class="control-label">End Date</label>
                <input type="date" wire:model="endDate" class="form-control">
            </div>

            <div class="control-group">
                <label class="control-label">&nbsp;</label>
                <button wire:click="computeReport" class="btn btn-apply">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </div>

            <div class="control-group">
                <label class="control-label">&nbsp;</label>
                <button wire:click="exportPdf" class="btn btn-export">
                    <i class="fas fa-file-pdf"></i> Export to PDF
                </button>
            </div>
        </div>

        <div class="table-card">
            <div class="table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>BSIS I</th>
                            <th>BSIS II</th>
                            <th>BSIS III</th>
                            <th>BSIS IV</th>
                            <th>Faculty/Admin</th>
                            <th>Carmenanon</th>
                            <th>Non-Carmenanon</th>
                            <th>S & S (Signs & Symptoms)</th>
                            <th>Dispensed Medications/Supplies</th>
                            <th>Services</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportRows as $row)
                            <tr class="data-row">
                                <td>{{ $row['date_label'] }}</td>
                                <td>{{ $row['male'] }}</td>
                                <td>{{ $row['female'] }}</td>
                                <td>{{ $row['bsis1'] }}</td>
                                <td>{{ $row['bsis2'] }}</td>
                                <td>{{ $row['bsis3'] }}</td>
                                <td>{{ $row['bsis4'] }}</td>
                                <td>{{ $row['faculty_admin'] }}</td>
                                <td>{{ $row['carmenanon'] }}</td>
                                <td>{{ $row['non_carmenanon'] }}</td>
                                <td class="text-list">{{ $row['complaints'] }}</td>
                                <td class="text-list">{{ $row['medicines'] }}</td>
                                <td class="text-list">{{ $row['services'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="empty-state">No data found for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="grand-total-row">
                            <td><strong>Grand Total</strong></td>
                            <td><strong>{{ $grandTotals['male'] }}</strong></td>
                            <td><strong>{{ $grandTotals['female'] }}</strong></td>
                            <td><strong>{{ $grandTotals['bsis1'] }}</strong></td>
                            <td><strong>{{ $grandTotals['bsis2'] }}</strong></td>
                            <td><strong>{{ $grandTotals['bsis3'] }}</strong></td>
                            <td><strong>{{ $grandTotals['bsis4'] }}</strong></td>
                            <td><strong>{{ $grandTotals['faculty_admin'] }}</strong></td>
                            <td><strong>{{ $grandTotals['carmenanon'] }}</strong></td>
                            <td><strong>{{ $grandTotals['non_carmenanon'] }}</strong></td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="report-footer">
            <p>Report generated from CMC Clinic Management System</p>
            <p>{{ now()->format('F d, Y H:i A') }}</p>
        </div>
    </div>

    <style>
        .clinic-report-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .report-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .report-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .report-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: flex-end;
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .control-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .form-control {
            background: var(--bg-input);
            border: 1px solid var(--border-input);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 13px;
            font-family: 'Figtree', sans-serif;
            color: var(--text-heading);
            transition: all 0.2s;
            min-width: 160px;
        }

        .form-control:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56,189,248,0.1);
        }

        .btn {
            border: none;
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
            font-family: 'Figtree', sans-serif;
        }

        .btn-apply {
            background: #3498db;
            color: white;
        }

        .btn-apply:hover {
            background: #2980b9;
        }

        .btn-export {
            background: #27ae60;
            color: white;
        }

        .btn-export:hover {
            background: #229954;
        }

        .table-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .report-table th {
            padding: 12px;
            text-align: left;
            background: var(--bg-input);
            font-weight: 700;
            color: var(--text-heading);
            border-bottom: 2px solid var(--border-inner);
            text-transform: uppercase;
            white-space: nowrap;
        }

        .report-table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-inner);
            color: var(--text-body);
            vertical-align: top;
        }

        .report-table tr:hover {
            background: var(--bg-input);
        }

        .text-list {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
        }

        .empty-state {
            text-align: center;
            color: var(--text-muted);
            padding: 32px;
            font-size: 13px;
        }

        .grand-total-row {
            background: var(--bg-input);
        }

        .grand-total-row td {
            border-top: 2px solid var(--border-inner);
            border-bottom: 2px solid var(--border-inner);
        }

        .report-footer {
            text-align: center;
            padding: 20px;
            border-top: 2px solid var(--border-inner);
            color: var(--text-muted);
            font-size: 12px;
        }

        .report-footer p {
            margin: 4px 0;
        }

        @media (max-width: 768px) {
            .report-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .form-control {
                min-width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</x-app-with-sidebar>
