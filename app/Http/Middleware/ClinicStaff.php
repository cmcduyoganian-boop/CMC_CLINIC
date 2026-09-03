<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClinicStaff
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $allowedRoles = $roles ?: ['clinic_staff', 'clinic_nurse'];

        if (Auth::check() && in_array(Auth::user()->role, $allowedRoles, true)) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access');
    }
}