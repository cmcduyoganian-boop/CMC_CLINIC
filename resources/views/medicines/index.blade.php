<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Medicines') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-semibold">Medicine Inventory</h3>
                        <a href="{{ route('medicines.create') }}" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">+ Add Medicine</a>
                    </div>

                    @if($medicines->count() > 0)
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Medicine Name</th>
                                    <th class="text-left py-2">Quantity</th>
                                    <th class="text-left py-2">Unit</th>
                                    <th class="text-left py-2">Expiry Date</th>
                                    <th class="text-left py-2">Status</th>
                                    <th class="text-left py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicines as $medicine)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2">{{ $medicine->name }}</td>
                                        <td class="py-2">{{ $medicine->quantity }}</td>
                                        <td class="py-2">{{ $medicine->unit }}</td>
                                        <td class="py-2">{{ $medicine->expiry_date ? $medicine->expiry_date->format('M d, Y') : 'N/A' }}</td>
                                        <td class="py-2">
                                            @if($medicine->quantity < 10)
                                                <span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded">Low Stock</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">In Stock</span>
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            <a href="{{ route('medicines.edit', $medicine) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                                            <form method="POST" action="{{ route('medicines.destroy', $medicine) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $medicines->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No medicines in inventory.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
