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
        'sms_reminder',
        'sms_message',
        'sms_status',
        'sms_sent_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'sms_reminder' => 'boolean',
        'sms_sent_at' => 'datetime',
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