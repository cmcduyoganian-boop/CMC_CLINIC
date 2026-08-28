<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
{
    return view('patients.index');
}
    public function show($id)
    {
        $patient = Patient::with(['clinicVisits' => function ($query) {
            $query->orderBy('visit_date', 'desc');
        }])->findOrFail($id);

        return view('patients.show', compact('patient'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function edit($id)
    {
        return view('patients.edit', ['patientId' => $id]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients',
            'phone' => 'nullable|string',
            'year_section' => 'nullable|string',
            'age' => 'nullable|integer',
            'category' => 'required|in:student,faculty,staff',
            'address' => 'nullable|string',
        ]);

        try {
            Patient::create($validated);

            return redirect()->route('patients.index')
                ->with('success', 'Patient ' . $validated['name'] . ' added successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to add patient. Please try again.');
        }
    }
}