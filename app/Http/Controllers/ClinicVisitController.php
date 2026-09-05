<?php

namespace App\Http\Controllers;

use App\Models\ClinicVisit;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicVisitController extends Controller
{
    public function index(Request $request)
    {
        [$rangeStart, $rangeEnd] = $this->resolveDateRange($request);

        $visits = ClinicVisit::with('patient')
            ->when($rangeStart && $rangeEnd, function ($query) use ($rangeStart, $rangeEnd) {
                $query->whereBetween('visit_date', [$rangeStart, $rangeEnd]);
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->trim()->toString();

                $query->where(function ($visitQuery) use ($search) {
                    $visitQuery->whereDate('visit_date', $search)
                        ->orWhere('complaints', 'like', "%{$search}%")
                        ->orWhere('diagnosis', 'like', "%{$search}%")
                        ->orWhere('management', 'like', "%{$search}%")
                        ->orWhereHas('patient', function ($patientQuery) use ($search) {
                            $patientQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('year_section', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('visit_date', 'desc')
            ->paginate(10)
            ->withQueryString();
        
        return view('clinic-visit.index', compact('visits'));
    }

    private function resolveDateRange(Request $request): array
    {
        $range = $request->string('date_range')->toString();
        $today = now();

        return match ($range) {
            'today' => [$today->copy()->startOfDay(), $today->copy()->endOfDay()],
            'yesterday' => [$today->copy()->subDay()->startOfDay(), $today->copy()->subDay()->endOfDay()],
            'last_7' => [$today->copy()->subDays(7)->startOfDay(), $today->copy()->endOfDay()],
            'last_30' => [$today->copy()->subDays(30)->startOfDay(), $today->copy()->endOfDay()],
            'this_month' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
            'custom' => $this->resolveCustomDateRange($request),
            default => [null, null],
        };
    }

    private function resolveCustomDateRange(Request $request): array
    {
        try {
            if (!$request->filled(['start_date', 'end_date'])) {
                return [null, null];
            }

            return [
                Carbon::createFromFormat('Y-m-d', (string) $request->string('start_date'))->startOfDay(),
                Carbon::createFromFormat('Y-m-d', (string) $request->string('end_date'))->endOfDay(),
            ];
        } catch (\Throwable) {
            return [null, null];
        }
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