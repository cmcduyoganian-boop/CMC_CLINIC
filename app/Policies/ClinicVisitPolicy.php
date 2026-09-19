<?php

namespace App\Policies;

use App\Models\ClinicVisit;
use App\Models\User;

class ClinicVisitPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['clinic_nurse', 'clinic_staff'], true);
    }

    public function view(User $user, ClinicVisit $visit): bool
    {
        if (in_array($user->role, ['clinic_nurse', 'clinic_staff'], true)) {
            return true;
        }

        if ($user->role === 'student') {
            return $visit->patient->email === $user->email;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['clinic_nurse', 'clinic_staff'], true);
    }

    public function update(User $user, ClinicVisit $visit): bool
    {
        if ($user->role === 'clinic_nurse') {
            return true;
        }

        if ($user->role === 'clinic_staff') {
            return true;
        }

        return false;
    }

    public function delete(User $user, ClinicVisit $visit): bool
    {
        return $user->role === 'clinic_nurse';
    }
}