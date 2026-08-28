<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'appointment_date',
        'appointment_time',
        'reason',
        'notes',
        'status',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'scheduled' => 'badge-scheduled',
            'completed' => 'badge-completed',
            'no-show' => 'badge-no-show',
            'cancelled' => 'badge-cancelled',
            default => 'badge-gray'
        };
    }

    public function getStatusLabel()
    {
        return ucfirst(str_replace('-', ' ', $this->status));
    }

    public function isUpcoming()
    {
        return $this->appointment_date >= today() && $this->status === 'scheduled';
    }
}