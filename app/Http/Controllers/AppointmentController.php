<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('appointments.index');
    }

    public function create()
    {
        return view('appointments.create');
    }

    public function store(Request $request)
    {
        // Handled by App\Livewire\Appointments\AppointmentCreateForm
    }

    public function show($id)
    {
        $appointment = Appointment::with('patient')->findOrFail($id);
        return view('appointments.show', compact('appointment'));
    }

    public function edit($id)
    {
        $appointment = Appointment::with('patient')->findOrFail($id);
        return view('appointments.edit', compact('appointment'));
    }

    public function update($id, Request $request)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,no-show,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Additional validation: if appointment is for today, time must be in the future
        if ($validated['appointment_date'] === now()->toDateString()) {
            $currentTime = now()->format('H:i');
            if ($validated['appointment_time'] <= $currentTime) {
                return back()->withErrors(['appointment_time' => 'For same-day appointments, the time must be after the current time (' . $currentTime . ').'])->withInput();
            }
        }

        try {
            $appointment->update($validated);

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update appointment.');
        }
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $patientName = $appointment->patient->name;
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment for ' . $patientName . ' deleted successfully!');
    }
}