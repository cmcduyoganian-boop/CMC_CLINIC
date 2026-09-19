<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicVisit;
use App\Models\Patient;
use Illuminate\Http\Request;

class RecentlyDeletedController extends Controller
{
    public function index()
    {
        $deletedPatients = Patient::onlyTrashed()->with(['clinicVisits' => function ($query) {
            $query->withTrashed();
        }, 'appointments' => function ($query) {
            $query->withTrashed();
        }])->orderByDesc('deleted_at')->get();

        $deletedVisits = ClinicVisit::onlyTrashed()->with('patient')->orderByDesc('deleted_at')->get();
        $deletedAppointments = Appointment::onlyTrashed()->with('patient')->orderByDesc('deleted_at')->get();

        return view('recently-deleted.index', compact(
            'deletedPatients',
            'deletedVisits',
            'deletedAppointments'
        ));
    }

    public function restorePatient(int $id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);

        if ($patient->trashed()) {
            $patient->restore();
        }

        return redirect()->route('recently-deleted.index')->with('success', 'Patient record restored successfully.');
    }

    public function forceDeletePatient(int $id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);

        if ($patient->trashed()) {
            $patient->forceDelete();
        }

        return redirect()->route('recently-deleted.index')->with('success', 'Patient record permanently deleted.');
    }

    public function restoreClinicVisit(int $id)
    {
        $visit = ClinicVisit::withTrashed()->findOrFail($id);

        if ($visit->trashed()) {
            $visit->restore();
        }

        return redirect()->route('recently-deleted.index')->with('success', 'Clinic visit restored successfully.');
    }

    public function forceDeleteClinicVisit(int $id)
    {
        $visit = ClinicVisit::withTrashed()->findOrFail($id);

        if ($visit->trashed()) {
            $visit->forceDelete();
        }

        return redirect()->route('recently-deleted.index')->with('success', 'Clinic visit permanently deleted.');
    }

    public function restoreAppointment(int $id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);

        if ($appointment->trashed()) {
            $appointment->restore();
        }

        return redirect()->route('recently-deleted.index')->with('success', 'Appointment restored successfully.');
    }

    public function forceDeleteAppointment(int $id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);

        if ($appointment->trashed()) {
            $appointment->forceDelete();
        }

        return redirect()->route('recently-deleted.index')->with('success', 'Appointment permanently deleted.');
    }

    public function clearAll()
    {
        $patients = Patient::onlyTrashed()->get();
        foreach ($patients as $patient) {
            $patient->forceDelete();
        }

        $visits = ClinicVisit::onlyTrashed()->get();
        foreach ($visits as $visit) {
            $visit->forceDelete();
        }

        $appointments = Appointment::onlyTrashed()->get();
        foreach ($appointments as $appointment) {
            $appointment->forceDelete();
        }

        return redirect()->route('recently-deleted.index')->with('success', 'All deleted records were permanently removed.');
    }
}
