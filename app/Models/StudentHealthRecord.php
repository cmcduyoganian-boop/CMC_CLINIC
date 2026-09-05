<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentHealthRecord extends Model
{
    protected $fillable = [
        'user_id',
        'student_code',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'maiden_name',
        'sex',
        'birthday',
        'birthplace',
        'blood_type',
        'mother_name',
        'father_name',
        'civil_status',
        'residential_address',
        'height',
        'weight',
        'course',
        'year_section',
        'contact_number',
        'spouse_name',
        'past_medical_history',
        'past_surgical_history',
        'family_history',
        'signature_name',
        'signature_date',
        'healthcare_provider_name',
    ];

    protected $casts = [
        'past_medical_history' => 'array',
        'past_surgical_history' => 'array',
        'family_history' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
