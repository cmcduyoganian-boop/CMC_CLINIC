<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function deleteAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $name = $appointment->patient->name;
        $appointment->delete();

        session()->flash('success', 'Appointment for ' . $name . ' deleted successfully!');
        $this->resetPage();
    }

    public function render()
    {
        $query = Appointment::with('patient');

        if ($this->search) {
            $search = $this->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(10);

        return view('livewire.appointments.appointment-list', [
            'appointments' => $appointments,
            'upcoming' => Appointment::where('appointment_date', '>=', today())->where('status', 'scheduled')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'total' => Appointment::count(),
        ]);
    }
}