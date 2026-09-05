<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Support\VitalSigns;

class ClinicVisit extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'visit_date',
        'visit_date',
        'visit_type',
        'address',
        'sex',
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
        'services',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'services' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getAddressAttribute()
    {
        if (array_key_exists('address', $this->attributes)) {
            return $this->attributes['address'];
        }

        return $this->patient?->address;
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

    /**
     * Returns per-vital classification + overall status.
     * Uses the centralized VitalSigns helper so thresholds stay in one place.
     */
    public function getVitalSignsAssessment(): array
    {
        $bmi = $this->getBMI();

        $statuses = [
            'temperature'      => VitalSigns::classifyTemperature($this->temperature !== null ? (float) $this->temperature : null),
            'pulse_rate'       => VitalSigns::classifyPulseRate($this->pulse_rate !== null ? (float) $this->pulse_rate : null),
            'respiratory_rate' => VitalSigns::classifyRespiratoryRate($this->respiratory_rate !== null ? (float) $this->respiratory_rate : null),
            'bp_systolic'      => VitalSigns::classifySystolic($this->bp_systolic !== null ? (float) $this->bp_systolic : null),
            'bp_diastolic'     => VitalSigns::classifyDiastolic($this->bp_diastolic !== null ? (float) $this->bp_diastolic : null),
            'spo2'             => VitalSigns::classifySpO2($this->spo2 !== null ? (float) $this->spo2 : null),
            'bmi'              => VitalSigns::classifyBMI($bmi !== null ? (float) $bmi : null),
        ];

        return [
            'statuses' => $statuses,
            'overall'  => VitalSigns::overallStatus($statuses),
        ];
    }
}