<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'username' => 'nullable|string|min:3|max:50|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'role' => 'nullable|in:student,faculty,staff,clinic_staff',
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ],
            [
                'username.unique' => 'This username is already taken.',
                'email.unique' => 'This email is already registered.',
                'password.min' => 'Password must be at least 6 characters.',
                'password.confirmed' => 'Passwords do not match.',
            ]
        );

        $validated['username'] ??= $this->generateUsername($validated['name'], $validated['email']);
        $validated['phone'] ??= '';
        $validated['role'] ??= 'student';

        // Check if user already exists with this email/username
        $existingUser = User::where('email', $validated['email'])
            ->orWhere('username', $validated['username'])
            ->first();

        if ($existingUser) {
            // If clinic_staff already exists, they should just log in
            if ($existingUser->role === 'clinic_staff') {
                return redirect()->route('login')
                    ->with('info', 'You already have a Clinic Staff account. Please log in with your existing credentials. Your patient record will be created automatically on first login.');
            }

            // For other roles, show specific error
            $errors = [];
            if (User::where('email', $validated['email'])->exists() || PendingRegistration::where('email', $validated['email'])->exists()) {
                $errors['email'] = 'This email is already registered.';
            }
            if (User::where('username', $validated['username'])->exists() || PendingRegistration::where('username', $validated['username'])->exists()) {
                $errors['username'] = 'This username is already taken.';
            }
            return back()->withInput()->withErrors($errors);
        }

        // Also check pending_registrations
        $existingPending = PendingRegistration::where('email', $validated['email'])
            ->orWhere('username', $validated['username'])
            ->first();

        if ($existingPending) {
            $errors = [];
            if (PendingRegistration::where('email', $validated['email'])->exists()) {
                $errors['email'] = 'This email is already registered.';
            }
            if (PendingRegistration::where('username', $validated['username'])->exists()) {
                $errors['username'] = 'This username is already taken.';
            }
            return back()->withInput()->withErrors($errors);
        }

        try {
            Log::info('✅ Validation passed');

            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
                'approval_status' => 'approved',
                'is_active' => true,
                'otp_verified' => true,
                'email_verified_at' => now(),
                'must_change_password' => false,
            ]);

            Auth::login($user);

            Log::info('✅ User created and authenticated', ['id' => $user->id, 'email' => $user->email]);

            return redirect()->route('dashboard')->with([
                'success' => 'Welcome! Your account has been created successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Registration error', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    protected function generateUsername(string $name, string $email): string
    {
        $base = Str::slug(str_replace(['.', '_'], ' ', $name), '');
        $base = $base !== '' ? $base : Str::before($email, '@');
        $base = preg_replace('/[^A-Za-z0-9]/', '', $base) ?: 'user';
        $base = strtolower(substr($base, 0, 20));

        $candidate = $base;
        $counter = 1;

        while (User::where('username', $candidate)->exists()) {
            $candidate = $base . $counter;
            $counter++;
        }

        return $candidate;
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
            'username' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:20',
            'clinic_name' => 'required|string|max:255',
            'password' => 'required|string|min:6|max:8|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
        ]);

        // Check if user already exists with this email/username
        $existingUser = User::where('email', $validated['email'])
            ->orWhere('username', $validated['username'])
            ->first();

        if ($existingUser) {
            if ($existingUser->role === 'clinic_staff') {
                return redirect()->route('login')
                    ->with('info', 'You already have a Clinic Staff account. Please log in with your existing credentials.');
            }
            $errors = [];
            if (User::where('email', $validated['email'])->exists() || PendingRegistration::where('email', $validated['email'])->exists()) {
                $errors['email'] = 'This email is already registered.';
            }
            if (User::where('username', $validated['username'])->exists() || PendingRegistration::where('username', $validated['username'])->exists()) {
                $errors['username'] = 'This username is already taken.';
            }
            return back()->withInput()->withErrors($errors);
        }

        $existingPending = PendingRegistration::where('email', $validated['email'])
            ->orWhere('username', $validated['username'])
            ->first();

        if ($existingPending) {
            $errors = [];
            if (PendingRegistration::where('email', $validated['email'])->exists()) {
                $errors['email'] = 'This email is already registered.';
            }
            if (PendingRegistration::where('username', $validated['username'])->exists()) {
                $errors['username'] = 'This username is already taken.';
            }
            return back()->withInput()->withErrors($errors);
        }

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