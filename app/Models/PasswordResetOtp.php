<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
    protected $fillable = ['user_id', 'email', 'otp', 'expires_at', 'used'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if OTP is valid
    public static function isValid($email, $otp)
    {
        return self::where('email', $email)
            ->where('otp', $otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
    }

    // Mark OTP as used
    public function markAsUsed()
    {
        $this->update(['used' => true]);
    }
}