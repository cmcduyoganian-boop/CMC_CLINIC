<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineInventoryLog extends Model
{
    protected $fillable = [
        'medicine_id',
        'action',
        'quantity',
        'notes',
        'recorded_by',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}