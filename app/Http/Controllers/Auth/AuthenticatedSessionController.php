<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Log::info('========== LOGIN ATTEMPT ==========', [
            'username_or_email' => $request->input('username'),
        ]);

        $request->authenticate();

        $request->session()->regenerate();

        // ✅ Bulk-created freshmen must change their default password on first login
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->must_change_password) {
            return redirect()->route('profile.change-password')
                ->with('info', '⚠️ You are using a default password. Please change it before continuing.');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}