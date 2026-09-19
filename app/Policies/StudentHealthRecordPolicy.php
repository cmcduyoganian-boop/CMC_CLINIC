<?php

namespace App\Policies;

use App\Models\StudentHealthRecord;
use App\Models\User;

class StudentHealthRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['clinic_nurse', 'clinic_staff'], true);
    }

    public function view(User $user, StudentHealthRecord $record): bool
    {
        if (in_array($user->role, ['clinic_nurse', 'clinic_staff'], true)) {
            return true;
        }

        if ($user->role === 'student') {
            return $record->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['student', 'clinic_nurse', 'clinic_staff'], true);
    }

    public function update(User $user, StudentHealthRecord $record): bool
    {
        if (in_array($user->role, ['clinic_nurse', 'clinic_staff'], true)) {
            return true;
        }

        if ($user->role === 'student') {
            return $record->user_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, StudentHealthRecord $record): bool
    {
        if ($user->role === 'clinic_nurse') {
            return true;
        }

        if ($user->role === 'student') {
            return $record->user_id === $user->id;
        }

        return false;
    }

    public function exportPdf(User $user, StudentHealthRecord $record): bool
    {
        if (in_array($user->role, ['clinic_nurse', 'clinic_staff'], true)) {
            return true;
        }

        if ($user->role === 'student') {
            return $record->user_id === $user->id;
        }

        return false;
    }
}