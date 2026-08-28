<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        Log::info('🟢 REGISTRATION STARTED');

        // ✅ UPDATED: Validate with username and updated password requirements
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'username' => 'required|string|min:3|max:50|unique:users,username|unique:pending_registrations,username',
                'email' => 'required|string|email|max:255|unique:users,email|unique:pending_registrations,email',
                'phone' => 'required|string|max:20',
                'role' => 'required|in:student,faculty,staff,clinic_staff',
                'password' => 'required|string|min:6|max:8|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
            ],
            [
                'username.unique' => 'This username is already taken.',
                'email.unique' => 'This email is already registered.',
                'password.min' => 'Password must be at least 6 characters.',
                'password.max' => 'Password cannot exceed 8 characters.',
                'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
                'password.confirmed' => 'Passwords do not match.',
            ]
        );

        try {
            Log::info('✅ Validation passed');

            // ✅ Create pending registration with username
            $pending = PendingRegistration::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
                'otp_verified' => false,
            ]);

            Log::info('✅ Pending registration created', ['id' => $pending->id]);

            // Generate OTP
            $otp = $pending->generateOtp();
            Log::info('✅ OTP generated: ' . $otp);

            // Send OTP email
            try {
                $pending->sendOtpEmail();
                Log::info('✅ OTP email sent successfully!');
            } catch (\Exception $emailError) {
                Log::error('❌ Email send failed', ['error' => $emailError->getMessage()]);
                return redirect()->route('otp.show', $pending->email)
                    ->with('error', 'We could not send the verification code. Please try Resend OTP.');
            }

            // Redirect to OTP verification page
            return redirect()->route('otp.show', $pending->email)->with([
                'success' => '✅ Check your email for a 6-digit verification code!',
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Registration error', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Display clinic staff registration view.
     */
    public function createClinicStaff(): View
    {
        return view('auth.register-clinic-staff');
    }

    /**
     * Handle clinic staff registration.
     */
    public function storeClinicStaff(Request $request): RedirectResponse
    {
        Log::info('🟢 CLINIC STAFF REGISTRATION STARTED');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|min:3|max:50|unique:users,username|unique:pending_registrations,username',
            'email' => 'required|string|email|max:255|unique:users,email|unique:pending_registrations,email',
            'phone' => 'required|string|max:20',
            'clinic_name' => 'required|string|max:255',
            'password' => 'required|string|min:6|max:8|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
        ]);

        try {
            $pending = PendingRegistration::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => 'clinic_staff',
                'password' => Hash::make($validated['password']),
                'otp_verified' => false,
            ]);

            session()->put('pending_clinic_name_' . $pending->email, $validated['clinic_name']);

            $otp = $pending->generateOtp();

            try {
                $pending->sendOtpEmail();
                Log::info('✅ Clinic staff OTP email sent');
            } catch (\Exception $emailError) {
                Log::error('❌ Clinic staff email failed', ['error' => $emailError->getMessage()]);
                return redirect()->route('otp.show', $pending->email)
                    ->with('error', 'We could not send the verification code. Please try Resend OTP.');
            }

            return redirect()->route('otp.show', $pending->email)->with([
                'success' => '✅ Check your email for verification code!',
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Clinic staff registration error', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }
}