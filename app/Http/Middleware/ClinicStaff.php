<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClinicStaff
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $allowedRoles = $roles ?: ['clinic_staff', 'clinic_nurse'];

        if (auth()->check() && in_array(auth()->user()->role, $allowedRoles, true)) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access');
    }
}