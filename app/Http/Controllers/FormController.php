<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function consent()
    {
        return view('forms.consent');
    }

    public function storeConsent(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone_number' => ['nullable', 'string', 'max:40'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_number' => ['nullable', 'string', 'max:40'],
            'client_signature' => ['required', 'string', 'max:255'],
            'client_signature_date' => ['required', 'date'],
            'guardian_signature' => ['nullable', 'string', 'max:255'],
            'guardian_signature_date' => ['nullable', 'date'],
        ]);

        FormSubmission::create([
            'user_id' => auth()->id(),
            'form_type' => 'client_research_consent',
            'data' => $data,
        ]);

        return redirect()->route('forms.consent')->with('success', 'Consent form saved successfully.');
    }

    public function clinicVisit()
    {
        return view('forms.clinic-visit');
    }

    public function studentInfo()
    {
        $submission = FormSubmission::where('user_id', auth()->id())
            ->where('form_type', 'student_medical_history')
            ->latest('submitted_at')
            ->first();

        return view('forms.student-info', ['savedData' => $submission?->data ?? []]);
    }

    public function storeStudentInfo(Request $request)
    {
        $data = $request->validate([
            'student_code' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'sex' => ['nullable', 'in:Male,Female'],
            'birthday' => ['nullable', 'string', 'max:30'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'blood_type' => ['nullable', 'string', 'max:20'],
            'civil_status' => ['nullable', 'string', 'max:40'],
            'contact_number' => ['nullable', 'string', 'max:40'],
            'spouse_name' => ['nullable', 'string', 'max:255'],
            'residential_address' => ['nullable', 'string', 'max:500'],
            'height' => ['nullable', 'string', 'max:20'],
            'weight' => ['nullable', 'string', 'max:20'],
            'course' => ['nullable', 'string', 'max:150'],
            'year_section' => ['nullable', 'string', 'max:100'],
        ]);

        $data = array_merge($request->except(['_token']), $data);

        FormSubmission::updateOrCreate(
            ['user_id' => auth()->id(), 'form_type' => 'student_medical_history'],
            ['data' => $data, 'submitted_at' => now()]
        );

        return redirect()->route('forms.student-info')->with('success', 'Student medical form saved successfully.');
    }

    public function storeClinicVisit(Request $request)
    {
        $data = $request->validate([
            'visit_date' => ['required', 'date'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'temperature' => ['nullable', 'numeric'],
            'pulse_rate' => ['nullable', 'numeric'],
            'respiratory_rate' => ['nullable', 'numeric'],
            'blood_pressure' => ['nullable', 'string', 'max:20'],
            'height' => ['nullable', 'numeric'],
            'weight' => ['nullable', 'numeric'],
            'bmi' => ['nullable', 'numeric'],
            'spo2' => ['nullable', 'numeric'],
            'complaints' => ['nullable', 'string', 'max:5000'],
            'management' => ['nullable', 'string', 'max:5000'],
            'diagnosis' => ['nullable', 'string', 'max:5000'],
            'signature' => ['nullable', 'string', 'max:255'],
        ]);

        FormSubmission::create([
            'user_id' => auth()->id(),
            'form_type' => 'clinic_visit_log',
            'data' => $data,
        ]);

        return redirect()->route('forms.clinic-visit')->with('success', 'Clinic visit form saved successfully.');
    }

    public function researchConsent()
    {
        return view('forms.research-consent');
    }

    public function storeResearchConsent(Request $request)
    {
        $data = $request->validate([
            'personnel_name' => ['required', 'string', 'max:255'],
            'course_year' => ['nullable', 'string', 'max:255'],
            'student_id' => ['nullable', 'string', 'max:100'],
            'signature' => ['nullable', 'string', 'max:255'],
            'consent_date' => ['required', 'date'],
            'witness_name' => ['nullable', 'string', 'max:255'],
            'witness_position' => ['nullable', 'string', 'max:255'],
            'witness_signature' => ['nullable', 'string', 'max:255'],
            'witness_date' => ['nullable', 'date'],
        ]);

        FormSubmission::create([
            'user_id' => auth()->id(),
            'form_type' => 'research_data_consent',
            'data' => $data,
        ]);

        return redirect()->route('forms.research-consent')->with('success', 'Research consent form saved successfully.');
    }
}
