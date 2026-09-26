<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Patient::class);
        return view('patients.index');
    }

    public function show(int|string $id)
    {
        $patient = Patient::with(['clinicVisits' => function ($query) {
            $query->orderBy('visit_date', 'desc');
        }])->findOrFail($id);

        $this->authorize('view', $patient);

        return view('patients.show', compact('patient'));
    }

    public function create()
    {
        $this->authorize('create', Patient::class);
        return view('patients.create');
    }

    public function edit(int|string $id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('update', $patient);
        return view('patients.edit', ['patientId' => $id]);
    }

    public function myProfile()
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        $patient = Patient::where('email', $user->email)->first();

        // Verify ownership by checking name similarity
        if ($patient) {
            $patientName = strtolower(trim($patient->name));
            $userName = strtolower(trim($user->name));
            similar_text($patientName, $userName, $percent);
            if ($percent < 70) {
                \Illuminate\Support\Facades\Log::warning('MyProfile access denied - name mismatch', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'patient_id' => $patient->id,
                    'similarity_percent' => $percent,
                ]);
                $patient = null;
            }
        }

        return view('patients.my-profile', compact('patient'));
    }

    public function myRecords()
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        $patient = Patient::where('email', $user->email)->with(['clinicVisits' => function ($query) {
            $query->orderBy('visit_date', 'desc');
        }])->first();

        // Verify ownership by checking name similarity
        if ($patient) {
            $patientName = strtolower(trim($patient->name));
            $userName = strtolower(trim($user->name));
            similar_text($patientName, $userName, $percent);
            if ($percent < 70) {
                \Illuminate\Support\Facades\Log::warning('MyRecords access denied - name mismatch', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'patient_id' => $patient->id,
                    'similarity_percent' => $percent,
                ]);
                $patient = null;
            }
        }

        if (!$patient) {
            return redirect()->route('dashboard')->with('info', 'You do not have a patient record yet.');
        }

        return view('patients.my-records', compact('patient'));
    }

    public function myAppointments()
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        $patient = Patient::where('email', $user->email)->with(['appointments' => function ($query) {
            $query->orderBy('appointment_date', 'desc')->orderBy('appointment_time', 'desc');
        }])->first();

        if ($patient) {
            $patientName = strtolower(trim($patient->name));
            $userName = strtolower(trim($user->name));
            similar_text($patientName, $userName, $percent);
            if ($percent < 70) {
                \Illuminate\Support\Facades\Log::warning('MyAppointments access denied - name mismatch', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'patient_id' => $patient->id,
                    'similarity_percent' => $percent,
                ]);
                $patient = null;
            }
        }

        if (!$patient) {
            return redirect()->route('dashboard')->with('info', 'You do not have a patient record yet.');
        }

        return view('patients.my-appointments', compact('patient'));
    }

    public function updateMyProfile(Request $request)
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        $patient = Patient::where('email', $user->email)->first();

        if (!$patient) {
            $roleCategoryMap = [
                'student' => 'student',
                'faculty' => 'faculty',
                'staff' => 'staff',
                'clinic_nurse' => 'student',
                'clinic_staff' => 'student',
            ];
            $patient = Patient::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'category' => $roleCategoryMap[$user->role] ?? 'student',
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
        $this->authorize('create', Patient::class);

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