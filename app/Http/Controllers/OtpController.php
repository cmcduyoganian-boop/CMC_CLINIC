<?php

namespace App\Http\Controllers;

use App\Models\PendingRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    /**
     * ✅ SHOW OTP VERIFICATION PAGE
     */
    public function show($email)
    {
        Log::info('========== OTP VERIFICATION PAGE REQUESTED ==========', ['email' => $email]);

        try {
            $pending = PendingRegistration::where('email', $email)->firstOrFail();

            Log::info('✅ Pending registration found', [
                'id' => $pending->id,
                'name' => $pending->name,
                'otp_verified' => $pending->otp_verified,
            ]);

            if ($pending->isOtpVerified()) {
                Log::info('Email already verified', ['email' => $email]);
                return redirect()->route('login')->with('info', 'Your email is already verified. Your account is pending admin approval.');
            }

            if ($pending->isRegistrationExpired()) {
                Log::warning('Registration expired', ['email' => $email]);
                $pending->delete();
                return redirect()->route('register')->with('error', 'Registration verification link has expired. Please register again.');
            }

            Log::info('✅ Rendering OTP verification page', ['email' => $email]);
            return view('auth.verify-otp', compact('pending'));

        } catch (\Exception $e) {
            Log::error('❌ Error showing OTP page', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('register')->with('error', 'Verification session not found. Please register again.');
        }
    }

    /**
     * ✅ VERIFY OTP AND CREATE USER ACCOUNT
     */
    public function verify(Request $request, $email)
    {
        Log::info('========== OTP VERIFICATION ATTEMPT ==========', ['email' => $email]);

        $otp = $request->input('otp') ?? '';
        
        if (is_array($otp)) {
            $otp = implode('', $otp);
        }

        Log::info('OTP input received', ['otp_input' => $otp, 'length' => strlen($otp)]);

        $validated = $request->validate([
            'otp' => 'required|string|size:6|regex:/^\d{6}$/',
        ], [
            'otp.size' => 'Verification code must be 6 digits.',
            'otp.regex' => 'Verification code must contain only numbers.',
        ]);

        try {
            $pending = PendingRegistration::where('email', $email)->firstOrFail();

            Log::info('Pending registration found for verification', [
                'id' => $pending->id,
                'stored_otp' => $pending->otp,
                'entered_otp' => $validated['otp'],
            ]);

            if ($pending->isOtpVerified()) {
                Log::info('OTP already verified', ['email' => $email]);
                return redirect()->route('login')->with('info', 'Email already verified. Please log in.');
            }

            if ($pending->isRegistrationExpired()) {
                Log::warning('Registration expired during verification', ['email' => $email]);
                $pending->delete();
                return redirect()->route('register')->with('error', 'Verification session expired. Please register again.');
            }

            // ✅ VERIFY OTP
            if (!$pending->verifyOtp($validated['otp'])) {
                Log::warning('OTP verification failed', [
                    'email' => $email,
                    'is_expired' => $pending->isOtpExpired(),
                ]);

                return back()
                    ->withErrors([
                        'otp' => $pending->isOtpExpired()
                            ? 'This verification code has expired. Please request a new one.'
                            : 'Invalid verification code. Please try again.',
                    ]);
            }

            Log::info('✅ OTP VERIFIED SUCCESSFULLY!', ['email' => $email]);

            // ✅ CREATE ACTUAL USER ACCOUNT (ONLY AFTER OTP IS VERIFIED)
            $user = $pending->createUser();

            if (!$user) {
                Log::error('❌ Failed to create user from pending registration', ['email' => $email]);
                return back()->with('error', 'Failed to create account. Please try again.');
            }

            Log::info('✅✅ USER ACCOUNT CREATED', [
                'user_id' => $user->id,
                'email' => $email,
                'approval_status' => $user->approval_status,
            ]);

            // If clinic staff, add clinic info
            if ($user->role === 'clinic_staff') {
                $clinicName = session()->get('pending_clinic_name_' . $email);
                if ($clinicName) {
                    $user->update(['clinic_name' => $clinicName]);
                    session()->forget('pending_clinic_name_' . $email);
                    Log::info('Clinic name added', ['email' => $email, 'clinic' => $clinicName]);
                }
            }

            // Delete pending registration
            $pending->delete();
            Log::info('✅ Pending registration deleted', ['email' => $email]);

            // ✅ REDIRECT TO LOGIN PAGE
            return redirect()->route('login')->with([
                'success' => '✅ Email verified successfully! Your account is created and pending admin approval. You will be able to log in once approved.',
                'email_verified' => true,
                'pending_approval' => true,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ OTP VERIFICATION ERROR', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * ✅ RESEND OTP
     */
    public function resend(Request $request)
    {
        Log::info('========== OTP RESEND REQUEST ==========', ['email' => $request->email]);

        $validated = $request->validate([
            'email' => 'required|email|exists:pending_registrations,email',
        ], [
            'email.exists' => 'This email is not found in pending registrations.',
        ]);

        try {
            $pending = PendingRegistration::where('email', $validated['email'])->firstOrFail();

            if ($pending->isOtpVerified()) {
                Log::info('Email already verified', ['email' => $validated['email']]);
                return back()->with('info', 'Your email is already verified. Please log in.');
            }

            if ($pending->isRegistrationExpired()) {
                Log::warning('Registration expired on resend', ['email' => $validated['email']]);
                $pending->delete();
                return redirect()->route('register')->with('error', 'Registration has expired. Please register again.');
            }

            try {
                Log::info('Resending OTP...');
                $otp = $pending->resendOtp();
                Log::info('✅ OTP resent', ['otp' => $otp]);

                return back()->with('success', '✅ A new verification code has been sent to your email.');

            } catch (\Exception $emailError) {
                Log::error('❌ Failed to resend OTP email', [
                    'error' => $emailError->getMessage(),
                ]);

                return back()->with('error', 'Failed to send verification code. Please check the mail configuration and try again.');
            }

        } catch (\Exception $e) {
            Log::error('❌ Resend OTP error', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'An error occurred. Please try again.');
        }
    }
}