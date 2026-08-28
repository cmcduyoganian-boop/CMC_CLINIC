<?php

use App\Models\PendingRegistration;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('creates a pending user only after otp verification completes', function () {
    Mail::fake();

    $pending = PendingRegistration::create([
        'name' => 'Jane Student',
        'email' => 'jane@example.com',
        'phone' => '09123456789',
        'role' => 'student',
        'password' => bcrypt('Secret123!'),
        'otp' => '123456',
        'otp_expires_at' => now()->addMinutes(10),
        'otp_verified' => true,
        'expires_at' => now()->addHours(24),
    ]);

    $user = $pending->createUser();

    expect($user)->not->toBeNull()
        ->and(User::count())->toBe(1)
        ->and(User::first()->status)->toBe('pending')
        ->and(User::first()->email_verified_at)->not->toBeNull();
});
