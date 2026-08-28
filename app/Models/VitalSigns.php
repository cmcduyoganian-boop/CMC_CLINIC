<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitalSigns extends Model
{
    protected $fillable = ['clinic_visit_id', 'temperature', 'blood_pressure', 'spo2', 'height', 'weight'];

    public function clinicVisit()
    {
        return $this->belongsTo(ClinicVisit::class);
    }
}