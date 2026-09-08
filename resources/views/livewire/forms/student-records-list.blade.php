<style>
    .shr-list-wrap { margin-top: 24px; }
    .shr-list-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 12px; flex-wrap: wrap; }
    .shr-list-title { font-size: 16px; font-weight: 700; color: #2d3e50; margin: 0 0 2px 0; }
    .shr-list-subtitle { font-size: 12px; color: #95a5a6; margin: 0; }
    .shr-search {
        width: 260px; max-width: 100%; font-size: 13px; padding: 9px 12px;
        border: 1px solid #d1d5db; border-radius: 8px; outline: none; background: #fff;
    }
    .shr-search:focus { border-color: #6366f1; box-shadow: 0 0 0 2px rgba(99,102,241,.15); }

    .shr-card {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08); overflow: hidden;
    }
    .shr-table-scroll { overflow-x: auto; }
    .shr-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .shr-table thead { background: #f9fafb; }
    .shr-table th {
        text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 700;
        color: #6b7280; text-transform: uppercase; letter-spacing: .03em;
        border-bottom: 1px solid #e5e7eb; white-space: nowrap;
    }
    .shr-table td {
        padding: 10px 16px; border-bottom: 1px solid #f3f4f6; color: #374151; white-space: nowrap;
    }
    .shr-table tbody tr:hover { background: #f9fafb; }
    .shr-table tbody tr:last-child td { border-bottom: none; }
    .shr-name { font-weight: 600; color: #1f2937; }
    .shr-empty { text-align: center; color: #9ca3af; padding: 28px 16px; }
    .shr-pagination { padding: 10px 16px; border-top: 1px solid #f3f4f6; }
</style>

<div class="shr-list-wrap">
    <div class="shr-list-header">
        <div>
            <p class="shr-list-title">Submitted Student Health Records</p>
            <p class="shr-list-subtitle">Names of students who have saved a Student Information Form.</p>
        </div>
        <input type="search" wire:model.live.debounce.300ms="search"
            placeholder="Search name, code, or course..." class="shr-search">
    </div>

    <div class="shr-card">
        <div class="shr-table-scroll">
            <table class="shr-table">
                <thead>
                    <tr>
                        <th>Student Code</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Year &amp; Section</th>
                        <th>Contact Number</th>
                        <th>Date Saved</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr wire:key="shr-{{ $record->id }}">
                            <td>{{ $record->student_code ?: '—' }}</td>
                            <td class="shr-name">
                                {{ trim($record->last_name . ', ' . $record->first_name . ' ' . $record->middle_name) }}
                            </td>
                            <td>{{ $record->course ?: '—' }}</td>
                            <td>{{ $record->year_section ?: '—' }}</td>
                            <td>{{ $record->contact_number ?: '—' }}</td>
                            <td>{{ $record->updated_at?->format('M d, Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="shr-empty">No student health records saved yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($records->hasPages())
            <div class="shr-pagination">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>