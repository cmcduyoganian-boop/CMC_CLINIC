<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'category',
        'condition_status',
        'description',
        'unit',
        'quantity',
        'minimum_stock',
        'expiration_date',
        'storage_location',
        'status',
    ];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    public function getStatusBadgeClass()
    {
        if ($this->quantity <= 0) {
            return 'badge-out-of-stock';
        } elseif ($this->quantity <= $this->minimum_stock) {
            return 'badge-low-stock';
        } else {
            return 'badge-good-stock';
        }
    }

    public function getStatusLabel()
    {
        if ($this->quantity <= 0) {
            return 'Out of Stock';
        } elseif ($this->quantity <= $this->minimum_stock) {
            return 'Low Stock';
        } else {
            return 'Good Stock';
        }
    }

    public function isExpired()
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    public function isExpiringSoon($days = 30)
    {
        if (!$this->expiration_date || $this->isExpired()) {
            return false;
        }

        return now()->diffInDays($this->expiration_date, false) <= $days;
    }

    public function inventoryLogs()
    {
        return $this->hasMany(MedicineInventoryLog::class);
    }

    public function latestInventoryLog()
    {
        return $this->hasOne(MedicineInventoryLog::class)->latestOfMany();
    }
}