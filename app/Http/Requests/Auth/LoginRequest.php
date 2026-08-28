<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => 'nullable|string|required_without:email',
            'email' => 'nullable|email|required_without:username',
            'password' => 'required|string',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $username = $this->input('username') ?: $this->input('email');
        $password = $this->input('password');

        Log::info('🔐 Authentication attempt', ['username' => $username]);

        // ✅ STEP 1: Find user by username or email
        $user = \App\Models\User::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$user) {
            Log::warning('❌ User not found', ['username' => $username]);
            $this->hits();
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        // ✅ STEP 2: Check if email is verified
        if (!$user->otp_verified) {
            Log::warning('❌ Email not verified', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            throw ValidationException::withMessages([
                'username' => '❌ Your email has not been verified. Please complete the email verification process first.',
            ]);
        }

        // ✅ STEP 3: Check if account is pending approval (CRITICAL SECURITY CHECK)
        if ($user->approval_status === 'pending') {
            Log::warning('❌ Account pending approval', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            throw ValidationException::withMessages([
                'username' => '⏳ Your account is pending Admin approval. Please wait for the Clinic Nurse to approve your account before logging in.',
            ]);
        }

        // ✅ STEP 4: Check if account is disabled
        if ($user->approval_status === 'disabled' || !$user->is_active) {
            Log::warning('❌ Account disabled', [
                'user_id' => $user->id,
                'email' => $user->email,
                'approval_status' => $user->approval_status,
                'is_active' => $user->is_active,
            ]);

            throw ValidationException::withMessages([
                'username' => '🚫 Your account has been disabled. Please contact the Clinic Nurse for assistance.',
            ]);
        }

        // ✅ STEP 5: Check if account is rejected
        if ($user->approval_status === 'rejected') {
            Log::warning('❌ Account rejected', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            throw ValidationException::withMessages([
                'username' => '❌ Your account registration has been rejected. Please contact the Clinic Nurse for more information.',
            ]);
        }

        // ✅ STEP 6: Verify password
        if (!password_verify($password, $user->password)) {
            Log::warning('❌ Invalid password', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            $this->hits();
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        // ✅ ALL CHECKS PASSED - Authenticate user
        Log::info('✅ Authentication successful', [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
        ]);

        Auth::login($user, $this->boolean('remember'));
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return strtolower($this->input('username')) . '|' . $this->ip();
    }

    /**
     * Get the number of attempts the user has made.
     */
    public function hits()
    {
        return RateLimiter::hit($this->throttleKey(), 60);
    }
}