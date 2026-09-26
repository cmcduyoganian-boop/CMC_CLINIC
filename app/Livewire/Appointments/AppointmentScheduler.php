<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AppointmentScheduler extends Component
{
    public $currentMonth;
    public $currentYear;
    public $selectedDate = null;
    public $showScheduleForm = false;

    public $patientId = '';
    public $patientName = '';
    public $patientCategory = '';
    public $patientYearSection = '';
    public $showPatientDropdown = false;

    public $appointmentDate = '';
    public $appointmentTime = '';
    public $reason = '';
    public $notes = '';
    public $smsReminder = false;
    public $smsMessage = '';

    // Appointment detail modal
    public $selectedAppointment = null;
    public $showAppointmentDetail = false;

    // Left panel tab: 'upcoming' | 'history' | 'day'
    public $listTab = 'upcoming';
    public $selectedDayLabel = '';

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->appointmentDate = now()->format('Y-m-d');
    }

    public function previousMonth()
    {
        $this->currentMonth--;
        if ($this->currentMonth < 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        }
        $this->selectedDate = null;
        if ($this->listTab === 'day') {
            $this->listTab = 'upcoming';
        }
    }

    public function nextMonth()
    {
        $this->currentMonth++;
        if ($this->currentMonth > 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        }
        $this->selectedDate = null;
        if ($this->listTab === 'day') {
            $this->listTab = 'upcoming';
        }
    }

    public function selectDate($day)
    {
        $this->selectedDate = sprintf('%04d-%02d-%02d', $this->currentYear, $this->currentMonth, $day);
        $this->appointmentDate = $this->selectedDate;
        $this->showScheduleForm = true;

        // Switch panel to show appointments on this day
        $this->selectedDayLabel = \Carbon\Carbon::parse($this->selectedDate)->format('F j, Y');
        $this->listTab = 'day';
    }

    public function setTab($tab)
    {
        $this->listTab = $tab;
        if ($tab !== 'day') {
            $this->selectedDate = null;
        }
    }

    public function updatedPatientName()
    {
        if ($this->patientName && !$this->patientId) {
            $this->showPatientDropdown = true;
        } else {
            $this->showPatientDropdown = false;
        }
    }

    public function selectPatient($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return;
        }

        $this->patientId = $patient->id;
        $this->patientName = $patient->name;
        $this->patientCategory = $patient->category;
        $this->patientYearSection = $patient->year_section ?? '';
        $this->showPatientDropdown = false;
    }

    public function hideDropdown()
    {
        $this->showPatientDropdown = false;
    }

    public function updatedSmsReminder()
    {
        if ($this->smsReminder) {
            $patientName = $this->patientName ?: 'Valued Patient';
            $date = Carbon::parse($this->appointmentDate)->format('F j, Y');
            $time = $this->appointmentTime ? Carbon::parse($this->appointmentTime)->format('g:i A') : '';
            $this->smsMessage = "Dear {$patientName}, this is a reminder of your clinic appointment on {$date} at {$time}. Please arrive 10 minutes early. - CMC Clinic";
        } else {
            $this->smsMessage = '';
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'patientName' => 'required|string|max:255',
            'patientCategory' => 'required|in:student,faculty,staff',
            'patientYearSection' => 'nullable|string|max:255',
            'appointmentDate' => 'required|date|after:today',
            'appointmentTime' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'smsReminder' => 'boolean',
            'smsMessage' => 'nullable|string|max:160',
        ]);

        $patient = $this->patientId
            ? Patient::find($this->patientId)
            : Patient::where('name', $validated['patientName'])->first();

        if (!$patient) {
            $patient = Patient::create([
                'name' => $validated['patientName'],
                'year_section' => $validated['patientYearSection'],
                'category' => $validated['patientCategory'],
                'status' => 'active',
            ]);
            $this->patientId = $patient->id;
        }

        Appointment::create([
            'patient_id' => $patient->id,
            'appointment_date' => $validated['appointmentDate'],
            'appointment_time' => $validated['appointmentTime'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'status' => 'scheduled',
            'sms_reminder' => $this->smsReminder,
            'sms_message' => $this->smsReminder ? $this->smsMessage : null,
            'sms_status' => $this->smsReminder ? 'pending' : null,
        ]);

        $this->reset([
            'patientId',
            'patientName',
            'patientCategory',
            'patientYearSection',
            'showPatientDropdown',
            'appointmentTime',
            'reason',
            'notes',
            'smsReminder',
            'smsMessage',
        ]);
        $this->showScheduleForm = false;

        $this->dispatch('notify', type: 'success', message: 'Appointment scheduled successfully!');
    }

    public function viewAppointment($appointmentId)
    {
        $this->selectedAppointment = Appointment::with('patient')->find($appointmentId);
        $this->showAppointmentDetail = true;
    }

    public function closeAppointmentDetail()
    {
        $this->showAppointmentDetail = false;
        $this->selectedAppointment = null;
    }

    public function markCompleted()
    {
        if ($this->selectedAppointment) {
            Appointment::findOrFail($this->selectedAppointment->id)->update(['status' => 'completed']);
            $this->dispatch('notify', type: 'success', message: 'Appointment marked as completed!');
            $this->closeAppointmentDetail();
        }
    }

public function markNoShow()
    {
        if ($this->selectedAppointment) {
            $this->selectedAppointment->update(['status' => 'no-show']);
            $this->dispatch('notify', type: 'warning', message: 'Appointment marked as no-show!');
            $this->closeAppointmentDetail();
        }
    }

    public function markCancelled()
    {
        if ($this->selectedAppointment) {
            $this->selectedAppointment->update(['status' => 'cancelled']);
            $this->dispatch('notify', type: 'warning', message: 'Appointment cancelled!');
            $this->closeAppointmentDetail();
        }
    }

    public function getCalendarDaysProperty()
    {
        $firstDay = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $startDay = $firstDay->copy()->startOfWeek();
        $endDay = $lastDay->copy()->endOfWeek();

        $days = [];
        for ($date = $startDay->copy(); $date->lte($endDay); $date->addDay()) {
            $days[] = [
                'date' => $date->copy(),
                'isCurrentMonth' => $date->month === $this->currentMonth,
                'day' => $date->day,
                'isToday' => $date->isToday(),
                'hasAppointment' => Appointment::whereDate('appointment_date', $date->toDateString())
                    ->where('status', 'scheduled')
                    ->exists(),
            ];
        }

        return $days;
    }

    public function getUpcomingAppointmentsProperty()
    {
        return Appointment::with('patient')
            ->where('appointment_date', '>=', now()->toDateString())
            ->where('status', 'scheduled')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->limit(20)
            ->limit(30)
            ->get();
    }

    public function getHistoryAppointmentsProperty()
    {
        return Appointment::with('patient')
            ->where(function ($q) {
                $q->where('appointment_date', '<', now()->toDateString())
                  ->orWhereIn('status', ['completed', 'no-show', 'cancelled']);
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->limit(50)
            ->get();
    }

    public function getDayAppointmentsProperty()
    {
        if (!$this->selectedDate) return collect();

        return Appointment::with('patient')
            ->whereDate('appointment_date', $this->selectedDate)
            ->orderBy('appointment_time', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.appointments.appointment-scheduler', [
            'calendarDays' => $this->calendarDays,
            'calendarDays'         => $this->calendarDays,
            'upcomingAppointments' => $this->upcomingAppointments,
            'historyAppointments'  => $this->historyAppointments,
            'dayAppointments'      => $this->dayAppointments,
        ]);
    }
}
