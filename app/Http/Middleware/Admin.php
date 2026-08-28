<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and is clinic_nurse (admin)
        if (auth()->check() && auth()->user()->role === 'clinic_nurse') {
            return $next($request);
        }

        // Redirect unauthorized users
        return redirect('/dashboard')->with('error', 'Unauthorized access - Admin only');
    }
}
