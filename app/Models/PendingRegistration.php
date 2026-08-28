<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Log;

class PendingRegistration extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected static function booted(): void
    {
        static::creating(function (PendingRegistration $pending) {
            if (!$pending->username) {
                $pending->username = $pending->generateUsername();
            }
        });
    }

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'role',
        'password',
        'clinic_name',
        'otp',
        'otp_expires_at',
        'otp_verified',
        'expires_at',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'expires_at' => 'datetime',
        'otp_verified' => 'boolean',
    ];

    // ============ CHECKS ============
    public function isOtpExpired(): bool
    {
        if (!$this->otp_expires_at) {
            return true;
        }
        return Carbon::now()->isAfter($this->otp_expires_at);
    }

    public function isRegistrationExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        return Carbon::now()->isAfter($this->expires_at);
    }

    public function isOtpVerified(): bool
    {
        return $this->otp_verified === true;
    }

    // ============ OTP METHODS ============
    public function generateOtp(): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'otp_verified' => false,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        Log::info('OTP Generated', [
            'email' => $this->email,
            'otp' => $otp,
            'expires_at' => $this->otp_expires_at,
        ]);

        return $otp;
    }

    public function sendOtpEmail(): bool
    {
        try {
            Log::info('Attempting to send OTP email', [
                'email' => $this->email,
                'name' => $this->name,
            ]);

            Mail::to($this->email)->send(new SendOtpMail($this->name, $this->otp));

            Log::info('OTP email sent successfully', [
                'email' => $this->email,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', [
                'email' => $this->email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function verifyOtp(string $otp): bool
    {
        if ($this->isOtpExpired()) {
            return false;
        }

        if ($this->otp !== $otp) {
            return false;
        }

        $this->update([
            'otp_verified' => true,
        ]);

        return true;
    }

    public function resendOtp(): string
    {
        $otp = $this->generateOtp();
        $this->sendOtpEmail();
        return $otp;
    }

    // ============ CREATE ACTUAL USER ============
    public function createUser(): ?User
    {
        if (!$this->isOtpVerified()) {
            Log::error('❌ Cannot create user - OTP not verified', ['email' => $this->email]);
            return null;
        }

        try {
            Log::info('🟢 Creating user from pending registration', [
                'email' => $this->email,
                'name' => $this->name,
                'role' => $this->role,
                'username_from_pending' => $this->username,
            ]);

            // ✅ Use username from pending registration OR generate one
            $username = $this->username;
            if (!$username) {
                $username = $this->generateUsername();
                Log::info('Generated username', ['username' => $username]);
            }

            // ✅ CREATE USER WITH CORRECT COLUMN NAMES
            $user = User::create([
                'name' => $this->name,
                'username' => $username,
                'email' => $this->email,
                'phone' => $this->phone,
                'role' => $this->role,
                'password' => $this->password,
                'approval_status' => 'pending',
                'is_active' => false,
                'otp_verified' => true,
                'is_bulk_created' => false,
                'must_change_password' => false,
                'email_verified_at' => now(),
            ]);

            if (!$user) {
                Log::error('❌ User::create() returned null', ['email' => $this->email]);
                return null;
            }

            Log::info('✅✅ USER CREATED SUCCESSFULLY', [
                'user_id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'approval_status' => $user->approval_status,
            ]);

            // Create patient record if student
            if ($this->role === 'student') {
                try {
                    Patient::firstOrCreate(
                        ['email' => $user->email],
                        [
                            'name' => $user->name,
                            'phone' => $user->phone,
                            'category' => 'student',
                            'status' => 'active',
                        ]
                    );
                    Log::info('Patient record created', ['user_id' => $user->id]);
                } catch (\Exception $e) {
                    Log::warning('Failed to create patient record', ['error' => $e->getMessage()]);
                }
            }

            return $user;

        } catch (\Exception $e) {
            Log::error('❌ Failed to create user', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }

    private function generateUsername(): string
    {
        $baseUsername = strtolower(str_replace(' ', '', substr($this->name, 0, 8)));
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists() || 
               self::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}