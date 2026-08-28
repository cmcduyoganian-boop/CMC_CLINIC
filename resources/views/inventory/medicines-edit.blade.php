<x-app-with-sidebar>
    <x-slot name="header">Edit Medicine</x-slot>

    <livewire:inventory.medicine-edit-form :medicine-id="$medicineId" />
</x-app-with-sidebar>