<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
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

    public function clinicVisits()
    {
        return $this->hasMany(ClinicVisit::class)->orderBy('visit_date', 'desc');
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