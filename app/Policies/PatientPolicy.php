<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['clinic_nurse', 'clinic_staff'], true);
    }

    public function view(User $user, Patient $patient): bool
    {
        if (in_array($user->role, ['clinic_nurse', 'clinic_staff'], true)) {
            return true;
        }

        if ($user->role === 'student') {
            return $patient->email === $user->email;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'clinic_nurse';
    }

    public function update(User $user, Patient $patient): bool
    {
        if ($user->role === 'clinic_nurse') {
            return true;
        }

        if ($user->role === 'clinic_staff') {
            return true;
        }

        if ($user->role === 'student') {
            return $patient->email === $user->email;
        }

        return false;
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->role === 'clinic_nurse';
    }

    public function viewRecords(User $user, Patient $patient): bool
    {
        if (in_array($user->role, ['clinic_nurse', 'clinic_staff'], true)) {
            return true;
        }

        if ($user->role === 'student') {
            return $patient->email === $user->email;
        }

        return false;
    }
}