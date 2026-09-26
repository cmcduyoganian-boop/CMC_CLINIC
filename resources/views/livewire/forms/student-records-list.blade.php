<style>
    .shr-list-wrap { margin-top: 24px; }
    .shr-list-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 12px; flex-wrap: wrap; }
    .shr-list-title { font-size: 16px; font-weight: 700; color: #2d3e50; margin: 0 0 2px 0; }
    .shr-list-subtitle { font-size: 12px; color: #95a5a6; margin: 0; }

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
        <input type="hidden" wire:model.live.debounce.300ms="search" class="livewire-search-input">
    </div>

    <div class="shr-card">
        <div class="shr-table-scroll">
            <table class="shr-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Year &amp; Section</th>
                        <th>Date Saved</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr wire:key="shr-{{ $record['id'] }}">
                            <td class="shr-name">
                                {{ trim(($record['last_name'] ?? '') . ', ' . ($record['first_name'] ?? '') . ' ' . ($record['middle_name'] ?? '')) ?: '—' }}
                            </td>
                            <td>{{ $record['course'] ?: '—' }}</td>
                            <td>{{ $record['year_section'] ?: '—' }}</td>
                            <td>{{ isset($record['saved_at']) && $record['saved_at'] ? \Carbon\Carbon::parse($record['saved_at'])->format('M d, Y g:i A') : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="shr-empty">No student health records saved yet.</td>
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