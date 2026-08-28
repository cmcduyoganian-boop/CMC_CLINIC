<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use Livewire\Component;

class AppointmentEditForm extends Component
{
    public $appointmentId;
    public $appointment;

    public $appointmentDate = '';
    public $appointmentTime = '';
    public $status = '';
    public $reason = '';
    public $notes = '';

    public function mount($appointmentId)
    {
        $this->appointmentId = $appointmentId;
        $this->appointment = Appointment::with('patient')->findOrFail($appointmentId);

        $this->appointmentDate = $this->appointment->appointment_date->format('Y-m-d');
        $this->appointmentTime = \Carbon\Carbon::parse($this->appointment->appointment_time)->format('H:i');
        $this->status = $this->appointment->status;
        $this->reason = $this->appointment->reason;
        $this->notes = $this->appointment->notes;
    }

    public function save()
    {
        $validated = $this->validate([
            'appointmentDate' => 'required|date',
            'appointmentTime' => 'required|date_format:H:i',
            'status' => 'required|in:scheduled,completed,no-show,cancelled',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $this->appointment->update([
            'appointment_date' => $validated['appointmentDate'],
            'appointment_time' => $validated['appointmentTime'],
            'status' => $validated['status'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
        ]);

        session()->flash('success', 'Appointment updated successfully!');

        return redirect()->route('appointments.index');
    }

    public function render()
    {
        return view('livewire.appointments.appointment-edit-form');
    }
}