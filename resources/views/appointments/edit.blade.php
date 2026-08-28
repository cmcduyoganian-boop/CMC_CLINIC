<x-app-with-sidebar>
    <x-slot name="header">Edit Appointment</x-slot>

    <livewire:appointments.appointment-edit-form :appointment-id="$appointment->id" />
</x-app-with-sidebar>