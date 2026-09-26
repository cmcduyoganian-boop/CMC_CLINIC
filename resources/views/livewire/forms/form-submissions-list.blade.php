<div class="form-submissions-list">
    <div class="fsl-header">
        <h2 class="fsl-title">
            <i class="fas fa-history"></i> Your Form Submissions
        </h2>
        <div class="fsl-filters">
            <select wire:model="filterType" class="fsl-filter">
                <option value="">All Types</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <input type="text" wire:model.debounce.300ms="search" class="fsl-search" placeholder="Search submissions...">
        </div>
    </div>

    @if($records->isEmpty())
        <div class="fsl-empty">
            <i class="fas fa-file-alt"></i>
            <p>No form submissions yet.</p>
            <small>Fill out a form above to see your submissions here.</small>
        </div>
    @else
        <div class="fsl-table-wrap">
            <table class="fsl-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Details</th>
                        <th>Saved</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>
                                <span class="fsl-badge fsl-{{ $record['form_type'] }}">{{ $record['type_label'] }}</span>
                            </td>
                            <td>
                                <div class="fsl-title-cell">{{ $record['title'] }}</div>
                            </td>
                            <td>
                                <div class="fsl-subtitle-cell">{{ $record['subtitle'] }}</div>
                            </td>
                            <td class="fsl-date-cell">
                                {{ $record['saved_at'] ? \Carbon\Carbon::parse($record['saved_at'])->format('M d, Y H:i') : '—' }}
                            </td>
                            <td>
                                <div class="fsl-actions">
                                    <a href="{{ route('forms.' . $this->getFormRoute($record['form_type'])) }}"
                                       class="fsl-btn fsl-btn-view" title="View/Edit">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button wire:click="deleteSubmission({{ $record['id'] }})"
                                            wire:confirm="Delete this form submission?"
                                            class="fsl-btn fsl-btn-delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="fsl-pagination">
            {{ $records->links() }}
        </div>
    @endif
</div>

<style>
    .form-submissions-list {
        margin-top: 16px;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 12px;
        padding: 20px;
    }

    .fsl-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-inner);
    }

    .fsl-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-heading);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .fsl-title i { color: #38bdf8; }

    .fsl-filters {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .fsl-filter, .fsl-search {
        border: 1px solid var(--border-input);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        background: var(--bg-input);
        color: var(--text-heading);
        min-width: 180px;
    }
    .fsl-filter:focus, .fsl-search:focus {
        outline: none;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56,189,248,0.1);
    }

    .fsl-empty {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
    .fsl-empty i { font-size: 32px; margin-bottom: 12px; display: block; }
    .fsl-empty p { margin: 0 0 8px; font-size: 14px; }
    .fsl-empty small { font-size: 12px; }

    .fsl-table-wrap { overflow-x: auto; }

    .fsl-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .fsl-table thead tr {
        background: var(--bg-input);
    }

    .fsl-table th {
        padding: 12px 14px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        border-bottom: 2px solid var(--border-inner);
    }

    .fsl-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--border-inner);
        vertical-align: middle;
    }

    .fsl-table tbody tr:last-child td { border-bottom: none; }
    .fsl-table tbody tr:hover { background: var(--bg-input); }

    .fsl-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }
    .fsl-student_medical_history { background: rgba(56,189,248,0.15); color: #38bdf8; }
    .fsl-clinic_visit_log { background: rgba(39,174,96,0.15); color: #27ae60; }
    .fsl-client_research_consent { background: rgba(243,156,18,0.15); color: #f39c12; }
    .fsl-research_data_consent { background: rgba(139,92,246,0.15); color: #8b5cf6; }

    .fsl-title-cell { font-weight: 600; color: var(--text-heading); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fsl-subtitle-cell { font-size: 12px; color: var(--text-muted); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fsl-date-cell { font-size: 12px; color: var(--text-muted); white-space: nowrap; }

    .fsl-actions { display: flex; gap: 8px; }
    .fsl-btn {
        width: 32px; height: 32px; border-radius: 6px;
        display: inline-flex; align-items: center; justify-content: center;
        color: #fff; text-decoration: none; font-size: 12px;
        transition: all 0.2s; border: none; cursor: pointer;
    }
    .fsl-btn-view { background: #38bdf8; }
    .fsl-btn-view:hover { background: #2563eb; transform: scale(1.05); }
    .fsl-btn-delete { background: #e74c3c; }
    .fsl-btn-delete:hover { background: #c0392b; transform: scale(1.05); }

    .fsl-pagination { margin-top: 16px; display: flex; justify-content: center; }

    @media (max-width: 768px) {
        .fsl-header { flex-direction: column; align-items: stretch; }
        .fsl-filters { flex-direction: column; }
        .fsl-filter, .fsl-search { min-width: 100%; }
        .fsl-table { font-size: 12px; }
        .fsl-table th, .fsl-table td { padding: 8px 10px; }
    }
</style>