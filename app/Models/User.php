<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'avatar',
        'role',
        'password',
        'approval_status',
        'is_active',
        'otp_verified',
        'is_bulk_created',
        'must_change_password',
        'email_verified_at',
        'clinic_name',
        'clinic_phone',
        'clinic_address',
        'clinic_hours',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'otp_verified' => 'boolean',
        'is_bulk_created' => 'boolean',
        'must_change_password' => 'boolean',
    ];

    // ============ SCOPES ============
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeFaculty($query)
    {
        return $query->where('role', 'faculty');
    }

    public function scopeStaff($query)
    {
        return $query->where('role', 'staff');
    }

    public function scopeClinicStaff($query)
    {
        return $query->where('role', 'clinic_staff');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // ============ CHECKS ============
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isDisabled(): bool
    {
        return $this->approval_status === 'disabled';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }

    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    public function isInactive(): bool
    {
        return $this->is_active === false;
    }

    public function getStatusAttribute(): string
    {
        return $this->approval_status;
    }

    public function isClinicStaff(): bool
    {
        return $this->role === 'clinic_staff';
    }

    public function canLogin(): bool
    {
        return $this->approval_status === 'approved' && 
               $this->is_active === true && 
               $this->otp_verified === true;
    }

    public function requiresApproval(): bool
    {
        return in_array($this->role, ['student', 'faculty', 'staff', 'clinic_staff']);
    }

    public function requiresEmailVerification(): bool
    {
        return in_array($this->role, ['student', 'faculty', 'staff']);
    }

    public function mustChangePassword(): bool
    {
        return $this->must_change_password === true;
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    // ============ ATTRIBUTES & LABELS ============
    public function getAvatarUrl(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    public function getInitial(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    public function getRoleLabel(): string
    {
        $labels = [
            'student' => 'Student',
            'faculty' => 'Faculty',
            'staff' => 'Staff',
            'clinic_nurse' => 'Clinic Nurse',
            'clinic_staff' => 'Clinic Staff',
        ];

        return $labels[$this->role] ?? ucfirst($this->role);
    }

    public function getStatusLabel(): string
    {
        $labels = [
            'pending' => 'Pending Approval',
            'approved' => 'Approved',
            'disabled' => 'Disabled',
            'rejected' => 'Rejected',
        ];

        return $labels[$this->approval_status] ?? ucfirst($this->approval_status);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->approval_status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'disabled' => 'badge-secondary',
            'rejected' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function getApprovalStatusLabel(): string
    {
        $labels = [
            'pending' => 'Pending Approval',
            'approved' => 'Approved',
            'disabled' => 'Disabled',
            'rejected' => 'Rejected',
        ];

        return $labels[$this->approval_status] ?? ucfirst($this->approval_status);
    }

    public function getApprovalStatusBadgeClass(): string
    {
        return match($this->approval_status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'disabled' => 'badge-secondary',
            'rejected' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function getActiveStatusBadgeClass(): string
    {
        return $this->is_active ? 'badge-success' : 'badge-danger';
    }

    public function getActiveStatusLabel(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }
}