<div class="mt-2">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h3 class="text-base font-bold text-gray-800">Submitted Student Health Records</h3>
            <p class="text-xs text-gray-500">Names of students who have saved a Student Information Form.</p>
        </div>
        <div class="w-64">
            <input type="search" wire:model.live.debounce.300ms="search"
                placeholder="Search name, code, or course..."
                class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold">Student Code</th>
                        <th class="px-4 py-2 text-left font-semibold">Name</th>
                        <th class="px-4 py-2 text-left font-semibold">Course</th>
                        <th class="px-4 py-2 text-left font-semibold">Year &amp; Section</th>
                        <th class="px-4 py-2 text-left font-semibold">Contact Number</th>
                        <th class="px-4 py-2 text-left font-semibold">Date Saved</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $record->student_code ?: '—' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap font-medium text-gray-800">
                                {{ trim($record->last_name . ', ' . $record->first_name . ' ' . $record->middle_name) }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $record->course ?: '—' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $record->year_section ?: '—' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $record->contact_number ?: '—' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-gray-500">{{ $record->updated_at?->format('M d, Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                                No student health records saved yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($records->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>