<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'year_section',
        'age',
        'address',
        'category',  // NEW: student, faculty, staff
        'program',   // e.g. BSOA, BSIS, BSCS
        'status',
    ];

    protected static function booted(): void
    {
        static::deleting(function (self $patient) {
            if ($patient->isForceDeleting()) {
                return;
            }

            $patient->clinicVisits()->withTrashed()->get()->each(fn ($visit) => $visit->delete());
            $patient->appointments()->withTrashed()->get()->each(fn ($appointment) => $appointment->delete());
        });

        static::restoring(function (self $patient) {
            $patient->clinicVisits()->withTrashed()->get()->each(fn ($visit) => $visit->restore());
            $patient->appointments()->withTrashed()->get()->each(fn ($appointment) => $appointment->restore());
        });
    }

    public function clinicVisits()
    {
        return $this->hasMany(ClinicVisit::class)->orderBy('visit_date', 'desc');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class)->orderBy('appointment_date', 'desc');
    }

    public function getCategoryBadgeClass()
    {
        return match($this->category) {
            'student' => 'badge-student',
            'faculty' => 'badge-faculty',
            'staff' => 'badge-staff',
            default => 'badge-gray'
        };
    }

    public function getCategoryLabel()
    {
        return ucfirst($this->category);
    }
}