<?php

namespace App\Http\Controllers;

use App\Models\ClinicVisit;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicVisitController extends Controller
{
    public function index()
    {
        $visits = ClinicVisit::with('patient')
            ->orderBy('visit_date', 'desc')
            ->paginate(10);
        
        return view('clinic-visit.index', compact('visits'));
    }

        public function create()
    {
        return view('clinic-visit.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_category' => 'required|in:student,faculty,staff',
            'patient_year_section' => 'nullable|string',
            'visit_date' => 'required|date',
            'complaints' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'management' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'pulse_rate' => 'nullable|numeric',
            'respiratory_rate' => 'nullable|numeric',
            'bp_systolic' => 'nullable|numeric',
            'bp_diastolic' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'spo2' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        try {
            // Find or create patient
            $patient = Patient::where('name', $validated['patient_name'])->first();
            
            if (!$patient) {
                $patient = Patient::create([
                    'name' => $validated['patient_name'],
                    'year_section' => $validated['patient_year_section'],
                    'category' => $validated['patient_category'],
                    'status' => 'active',
                ]);
            }

            // Create clinic visit
            ClinicVisit::create([
                'patient_id' => $patient->id,
                'user_id' => Auth::id(),
                'visit_date' => $validated['visit_date'],
                'complaints' => $validated['complaints'],
                'diagnosis' => $validated['diagnosis'],
                'management' => $validated['management'],
                'temperature' => $validated['temperature'],
                'pulse_rate' => $validated['pulse_rate'],
                'respiratory_rate' => $validated['respiratory_rate'],
                'bp_systolic' => $validated['bp_systolic'],
                'bp_diastolic' => $validated['bp_diastolic'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'spo2' => $validated['spo2'],
                'notes' => $validated['notes'],
            ]);

            return redirect()->route('clinic-visit.index')
                ->with('success', 'Clinic visit recorded successfully! Patient: ' . $patient->name);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to save clinic visit. Please try again.');
        }
    }

    public function show(int $id)
    {
        $visit = ClinicVisit::with('patient.clinicVisits')->findOrFail($id);
        return view('clinic-visit.show', compact('visit'));
    }

    public function edit(int $id)
    {
        return view('clinic-visit.edit', ['visitId' => $id]);
    }

    public function update(Request $request, int $id)
    {
        $visit = ClinicVisit::findOrFail($id);

        $validated = $request->validate([
            'visit_date' => 'required|date',
            'complaints' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'management' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'pulse_rate' => 'nullable|numeric',
            'respiratory_rate' => 'nullable|numeric',
            'bp_systolic' => 'nullable|numeric',
            'bp_diastolic' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'spo2' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $visit->update($validated);

        return redirect()->route('clinic-visit.index')
            ->with('success', 'Clinic visit updated successfully!');
    }

    public function destroy(int $id)
    {
        $visit = ClinicVisit::findOrFail($id);
        $visit->delete();

        return redirect()->route('clinic-visit.index')
            ->with('success', 'Clinic visit deleted successfully!');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $patients = Patient::where('name', 'like', "%$query%")
            ->orWhere('year_section', 'like', "%$query%")
            ->get();

        return response()->json($patients);
    }
}