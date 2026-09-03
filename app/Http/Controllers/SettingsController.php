<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    // ============ SHOW SETTINGS ============
    public function index()
    {
        $user = auth()->user();
        return view('settings.index', compact('user'));
    }

    // ============ UPDATE PROFILE PICTURE ============
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        try {
            $user = auth()->user();

            // Delete the old avatar file if one exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store the new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);

            return back()->with('success', 'Profile picture updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile picture.');
        }
    }

    // ============ REMOVE PROFILE PICTURE ============
    public function deleteAvatar(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->update(['avatar' => null]);

            return back()->with('success', 'Profile picture removed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove profile picture.');
        }
    }

    // ============ UPDATE PROFILE ============
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'required|string|max:20',
        ]);

        try {
            auth()->user()->update($validated);
            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile.');
        }
    }

    // ============ UPDATE USERNAME ============
    public function updateUsername(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
        ]);

        try {
            auth()->user()->update($validated);
            return back()->with('success', 'Username updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update username.');
        }
    }

    // ============ REQUEST PASSWORD CHANGE OTP ============
    public function requestPasswordOtp(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        try {
            // Generate OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Delete old OTPs
            PasswordResetOtp::where('user_id', $user->id)->delete();

            // Create new OTP
            PasswordResetOtp::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
            ]);

            // Send OTP via email
            $this->sendOtpEmail($user, $otp);

            return back()->with([
                'success' => 'OTP sent to your email! Enter it below to proceed.',
                'step' => 'verify_otp',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    // ============ UPDATE PASSWORD WITH OTP VERIFICATION ============
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

            // If OTP is set, this is password change request
            if ($request->has('otp')) {
                $request->validate([
                    'otp' => 'required|string|size:6',
                    'password' => ['required', 'confirmed', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/', 'min:6', 'max:8'],
                ], [
                    'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
                    'password.min' => 'Password must be at least 6 characters.',
                    'password.max' => 'Password cannot exceed 8 characters.',
                ]);

            // Verify OTP
            $otpRecord = PasswordResetOtp::isValid($user->email, $request->otp);

            if (!$otpRecord) {
                return back()->with('error', 'Invalid or expired OTP.');
            }

            try {
                // Mark OTP as used
                $otpRecord->markAsUsed();

                // Update password
                $user->update(['password' => Hash::make($request->password)]);

                return back()->with('success', 'Password changed successfully!');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to update password.');
            }
        }

        // If no OTP, just validate current password
        $request->validate([
            'current_password' => 'required|string',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        return back()->with('step', 'verify_otp');
    }

    // ============ UPDATE CLINIC INFO ============
    public function updateClinic(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => 'nullable|string|max:255',
            'clinic_phone' => 'nullable|string|max:20',
            'clinic_address' => 'nullable|string|max:500',
            'clinic_hours' => 'nullable|string|max:100',
        ]);

        try {
            auth()->user()->update($validated);
            return back()->with('success', 'Clinic information updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update clinic information.');
        }
    }

    // ============ SEND OTP EMAIL ============
    private function sendOtpEmail($user, $otp)
    {
        $subject = 'Password Change Verification - CMC Clinic';
        $message = "
            <h2>Password Change Request</h2>
            <p>Hello {$user->name},</p>
            <p>You requested to change your password. Use the OTP below to verify your change:</p>
            <div style='background: #f5f5f5; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0;'>
                <h1 style='color: #1a3a52; letter-spacing: 4px; margin: 0;'>{$otp}</h1>
                <p style='color: #999; margin: 10px 0 0 0;'>Valid for 10 minutes</p>
            </div>
            <p>If you did not request this, please contact the clinic administrator immediately.</p>
            <p>
                Best regards,<br>
                CMC School Clinic System
            </p>
        ";

        Mail::send([], [], function ($msg) use ($user, $subject, $message) {
            $msg->to($user->email)
                ->subject($subject)
                ->html($message);
        });
    }
}