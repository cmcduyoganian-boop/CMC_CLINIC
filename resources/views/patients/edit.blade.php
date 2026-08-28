<x-app-with-sidebar>
    <x-slot name="header">Edit Patient</x-slot>

    <livewire:patients.patient-edit-form :patient-id="$patientId" />
</x-app-with-sidebar>