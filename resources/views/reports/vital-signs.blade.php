<x-app-with-sidebar>
	<x-slot name="header">Vital Signs Report</x-slot>

	<div class="report-page">
		<div class="report-header">
			<div>
				<h1 class="report-title">Vital Signs Report</h1>
				<p class="report-subtitle">Recorded vital signs and health assessments</p>
			</div>
			<div class="header-actions">
				<button type="button" onclick="window.print()" class="btn btn-print">
					<i class="fas fa-print"></i> Print
				</button>
				<a href="{{ route('reports.download', 'vital-signs') }}" class="btn btn-download">
					<i class="fas fa-file-download"></i> Download Excel
				</a>
				<a href="{{ route('reports.index') }}" class="btn btn-back">
					<i class="fas fa-arrow-left"></i> Back
				</a>
			</div>
		</div>

		<div class="summary-cards">
			<div class="card status-card clickable-card" data-filter="all">
				<h3>Total Readings</h3>
				<p class="card-value">{{ $allReadings->count() }}</p>
			</div>
			<div class="card status-card clickable-card" data-filter="normal">
				<h3>Normal</h3>
				<p class="card-value status-normal">{{ $normalCount }}</p>
			</div>
			<div class="card status-card clickable-card" data-filter="above_normal">
				<h3>Above Normal</h3>
				<p class="card-value status-above">{{ $aboveCount }}</p>
			</div>
			<div class="card status-card clickable-card" data-filter="below_normal">
				<h3>Below Normal</h3>
				<p class="card-value status-below">{{ $belowCount }}</p>
			</div>
			<div class="card status-card clickable-card" data-filter="abnormal">
				<h3>Abnormal / Critical</h3>
				<p class="card-value status-abnormal">{{ $abnormalCount }}</p>
			</div>
		</div>

		<div class="alert-summary">
			<strong>Threshold alerts:</strong>
			Fever {{ $highFever }}, low temperature {{ $lowTemperature }}, low SpO2 {{ $lowOxygen }},
			blood pressure {{ $highBP + $lowBP }}, pulse {{ $highPulse + $lowPulse }},
			respiratory rate {{ $highRespRate + $lowRespRate }}.
		</div>

		<div class="table-card">
			<div class="table-heading">
				<h2 class="table-title">Recorded Vital Signs</h2>
				<button type="button" id="clearFilterBtn" class="btn-clear-filter" hidden>
					<i class="fas fa-times-circle"></i> Clear Filter
				</button>
			</div>
			<div class="table-wrapper">
				<table class="report-table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Patient</th>
							<th>Temperature</th>
							<th>Pulse</th>
							<th>Respiratory</th>
							<th>Blood Pressure</th>
							<th>SpO2</th>
							<th>BMI</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($allReadings as $reading)
							@php
								$assessment = $reading->getVitalSignsAssessment();
								$overall = $assessment['overall'];
								$statusLabel = $overall ? \App\Support\VitalSigns::label($overall) : 'NO DATA';
							@endphp
							<tr class="data-row" data-status="{{ $overall ?? 'none' }}">
								<td>{{ $reading->visit_date->format('M d, Y') }}</td>
								<td><strong>{{ $reading->patient->name ?? 'N/A' }}</strong></td>
								<td>{{ $reading->temperature !== null ? $reading->temperature . '°C' : '-' }}</td>
								<td>{{ $reading->pulse_rate !== null ? $reading->pulse_rate . ' bpm' : '-' }}</td>
								<td>{{ $reading->respiratory_rate !== null ? $reading->respiratory_rate . ' /min' : '-' }}</td>
								<td>{{ $reading->bp_systolic !== null && $reading->bp_diastolic !== null ? $reading->bp_systolic . '/' . $reading->bp_diastolic : '-' }}</td>
								<td>{{ $reading->spo2 !== null ? $reading->spo2 . '%' : '-' }}</td>
								<td>{{ $reading->getBMI() ?? '-' }}</td>
								<td>
									@if ($overall)
										<span class="status-badge status-{{ $overall }}">
											{{ \App\Support\VitalSigns::icon($overall) }} {{ $statusLabel }}
										</span>
									@else
										<span class="status-badge status-none">NO DATA</span>
									@endif
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="9" class="empty-state">No vital signs have been recorded yet.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<div class="report-footer">
			<p>Report generated from CMC Clinic Management System</p>
			<p>{{ now()->format('F d, Y H:i A') }}</p>
		</div>
	</div>

	<style>
		.report-page { display: flex; flex-direction: column; gap: 24px; }
		.report-header, .table-heading { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
		.report-title { margin: 0; font-size: 28px; font-weight: 700; color: var(--text-heading); }
		.report-subtitle { margin: 4px 0 0; font-size: 13px; color: var(--text-muted); }
		.header-actions { display: flex; gap: 12px; flex-wrap: wrap; }
		.btn { border: 0; border-radius: 8px; padding: 10px 16px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; text-decoration: none; }
		.btn-print { background: #3498db; color: #fff; }
		.btn-download { background: #27ae60; color: #fff; }
		.btn-back { background: var(--bg-input); color: var(--text-body); }
		.summary-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px; }
		.card { background: var(--bg-card); border-radius: 10px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.2); text-align: center; }
		.clickable-card { cursor: pointer; border: 2px solid transparent; transition: .2s; }
		.clickable-card:hover, .clickable-card.active { border-color: #38bdf8; transform: translateY(-2px); }
		.card h3 { margin: 0; font-size: 12px; color: var(--text-muted); text-transform: uppercase; }
		.card-value { margin: 12px 0 0; font-size: 30px; font-weight: 700; color: #38bdf8; }
		.status-normal { color: #27ae60; } .status-above { color: #d97706; } .status-below { color: #2563eb; } .status-abnormal { color: #dc2626; }
		.alert-summary, .table-card { background: var(--bg-card); border-radius: 10px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.2); color: var(--text-body); font-size: 13px; }
		.alert-summary { border-left: 4px solid #f59e0b; }
		.table-title { margin: 0 0 16px; font-size: 16px; color: var(--text-heading); }
		.btn-clear-filter { border: 0; background: transparent; color: #dc2626; cursor: pointer; font-weight: 600; }
		.table-wrapper { overflow-x: auto; }
		.report-table { width: 100%; border-collapse: collapse; font-size: 12px; }
		.report-table th { padding: 12px; text-align: left; background: var(--bg-input); color: var(--text-heading); font-size: 11px; text-transform: uppercase; white-space: nowrap; }
		.report-table td { padding: 12px; border-bottom: 1px solid var(--border-inner); color: var(--text-body); white-space: nowrap; }
		.report-table tr.hidden { display: none; }
		.status-badge { display: inline-block; padding: 5px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; white-space: nowrap; }
		.status-badge.status-normal { background: rgba(39,174,96,.15); color: #16803c; }
		.status-badge.status-above_normal { background: rgba(217,119,6,.15); color: #b45309; }
		.status-badge.status-below_normal { background: rgba(37,99,235,.15); color: #1d4ed8; }
		.status-badge.status-abnormal { background: rgba(220,38,38,.15); color: #b91c1c; }
		.status-none { background: var(--bg-input); color: var(--text-muted); }
		.empty-state { text-align: center; padding: 32px !important; }
		.report-footer { color: var(--text-muted); font-size: 12px; text-align: center; }
		.report-footer p { margin: 4px; }
		@media (max-width: 700px) { .report-header { flex-direction: column; } .header-actions { width: 100%; } .btn { flex: 1; justify-content: center; } }
		@media print { .header-actions, .btn-clear-filter { display: none !important; } .report-page { gap: 12px; } .table-card, .card { box-shadow: none; } }
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const cards = document.querySelectorAll('.clickable-card');
			const rows = document.querySelectorAll('.data-row');
			const clearButton = document.getElementById('clearFilterBtn');
			let activeFilter = null;

			function applyFilter(filter) {
				activeFilter = filter;
				cards.forEach(card => card.classList.toggle('active', card.dataset.filter === filter));
				rows.forEach(row => row.classList.toggle('hidden', filter !== 'all' && row.dataset.status !== filter));
				clearButton.hidden = !filter || filter === 'all';
			}

			cards.forEach(card => card.addEventListener('click', function () {
				applyFilter(activeFilter === this.dataset.filter ? null : this.dataset.filter);
			}));
			clearButton.addEventListener('click', () => applyFilter(null));
		});
	</script>
</x-app-with-sidebar>