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

    public function myProfile()
    {
        $user = auth()->user();
        $patient = Patient::where('email', $user->email)->first();

        return view('patients.my-profile', compact('patient'));
    }

    public function myRecords()
    {
        $user = auth()->user();
        $patient = Patient::where('email', $user->email)->with(['clinicVisits' => function ($query) {
            $query->orderBy('visit_date', 'desc');
        }])->first();

        if (!$patient) {
            return redirect()->route('dashboard')->with('info', 'You do not have a patient record yet.');
        }

        return view('patients.my-records', compact('patient'));
    }

    public function updateMyProfile(Request $request)
    {
        $user = auth()->user();
        $patient = Patient::where('email', $user->email)->first();

        if (!$patient) {
            $patient = Patient::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'category' => $user->role ?? 'student',
                'status' => 'active',
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:1|max:120',
            'category' => 'required|in:student,faculty,staff',
            'program' => 'nullable|string|max:255',
            'year_section' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $patient->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'age' => $validated['age'] ?? null,
            'category' => $validated['category'],
            'program' => $validated['program'] ?? null,
            'year_section' => $validated['year_section'] ?? null,
            'address' => $validated['address'] ?? null,
            'email' => $user->email,
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Your profile was updated successfully.');
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