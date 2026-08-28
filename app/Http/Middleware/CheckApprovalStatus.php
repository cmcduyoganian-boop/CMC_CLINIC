<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckApprovalStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is not authenticated, proceed
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Check if pending approval
        if ($user->approval_status === 'pending') {
            Log::warning('🚫 Pending user trying to access protected route', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            Auth::logout();
            return redirect()->route('login')->with('error', '⏳ Your account is pending admin approval. Please wait for the Clinic Nurse to approve your account.');
        }

        // Check if disabled
        if ($user->approval_status === 'disabled' || !$user->is_active) {
            Log::warning('🚫 Disabled user trying to access protected route', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            Auth::logout();
            return redirect()->route('login')->with('error', '🚫 Your account has been disabled. Please contact the Clinic Nurse for assistance.');
        }

        // Check if rejected
        if ($user->approval_status === 'rejected') {
            Log::warning('🚫 Rejected user trying to access protected route', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            Auth::logout();
            return redirect()->route('login')->with('error', '❌ Your account has been rejected. Please contact the Clinic Nurse for more information.');
        }

        return $next($request);
    }
}