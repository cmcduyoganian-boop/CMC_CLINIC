<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ClinicVisit extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'visit_date',
        'visit_date',
        'visit_type',
        'complaints',
        'diagnosis',
        'management',
        'temperature',
        'pulse_rate',
        'respiratory_rate',
        'bp_systolic',
        'bp_diastolic',
        'height',
        'weight',
        'bmi',
        'spo2',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBMI()
    {
        if ($this->height && $this->weight) {
            $heightM = $this->height / 100;
            return round(($this->weight / ($heightM * $heightM)), 1);
        }
        return null;
    }
}