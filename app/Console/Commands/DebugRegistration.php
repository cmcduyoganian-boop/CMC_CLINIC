<?php

namespace App\Console\Commands;

use App\Models\PendingRegistration;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class DebugRegistration extends Command
{
    protected $signature = 'debug:registration {email?}';
    protected $description = 'Debug entire registration → OTP flow';

    public function handle()
    {
        $testEmail = $this->argument('email') ?? 'test-' . time() . '@example.com';
        
        $this->line('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('📋 REGISTRATION → OTP FLOW DIAGNOSTIC');
        $this->info('═══════════════════════════════════════════════════════════');

        // ✅ CHECK 1: Routes exist
        $this->line('');
        $this->info('🔍 CHECK 1: Routes Registered');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        $routes = collect(Route::getRoutes())->map(fn($r) => $r->getName())->filter();
        
        $requiredRoutes = [
            'register',
            'register.store',
            'otp.show',
            'otp.verify',
            'otp.resend',
            'login',
        ];

        foreach ($requiredRoutes as $route) {
            $exists = $routes->contains($route);
            $this->line($route . ': ' . ($exists ? '✅ EXISTS' : '❌ MISSING'));
        }

        // ✅ CHECK 2: Database tables
        $this->line('');
        $this->info('🔍 CHECK 2: Database Tables');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        $pendingExists = DB::connection()->getSchemaBuilder()->hasTable('pending_registrations');
        $usersExists = DB::connection()->getSchemaBuilder()->hasTable('users');
        
        $this->line('pending_registrations: ' . ($pendingExists ? '✅ EXISTS' : '❌ MISSING'));
        $this->line('users: ' . ($usersExists ? '✅ EXISTS' : '❌ MISSING'));

        if ($pendingExists) {
            $columns = DB::connection()->getSchemaBuilder()->getColumnListing('pending_registrations');
            $this->line('  Columns: ' . implode(', ', $columns));
        }

        // ✅ CHECK 3: Mail configuration
        $this->line('');
        $this->info('🔍 CHECK 3: Mail Configuration');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        $this->line('MAIL_MAILER: ' . config('mail.mailer'));
        $this->line('QUEUE_CONNECTION: ' . config('queue.default'));
        
        if (config('queue.default') !== 'sync') {
            $this->error('❌ QUEUE is not sync! Emails will not send immediately!');
        } else {
            $this->info('✅ QUEUE is sync');
        }

        // ✅ CHECK 4: Clean up test data
        $this->line('');
        $this->info('🔍 CHECK 4: Cleaning test data');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        PendingRegistration::where('email', $testEmail)->delete();
        User::where('email', $testEmail)->delete();
        $this->info('✅ Old test data deleted');

        // ✅ CHECK 5: Test complete registration flow
        $this->line('');
        $this->info('🔍 CHECK 5: Testing Registration Flow');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        
        try {
            // Create pending registration
            $this->line('Step 1: Creating pending registration...');
            $pending = PendingRegistration::create([
                'name' => 'Test User',
                'email' => $testEmail,
                'phone' => '09123456789',
                'role' => 'student',
                'password' => Hash::make('TestPass123!'),
                'otp_verified' => false,
            ]);
            $this->info('✅ Pending registration created: ID ' . $pending->id);

            // Generate OTP
            $this->line('Step 2: Generating OTP...');
            $otp = $pending->generateOtp();
            $this->info('✅ OTP generated: ' . $otp);
            $this->line('   Expires: ' . $pending->otp_expires_at);

            // Send email
            $this->line('Step 3: Sending OTP email...');
            try {
                $pending->sendOtpEmail();
                $this->info('✅ OTP email sent to: ' . $testEmail);
            } catch (\Exception $e) {
                $this->error('❌ Email failed: ' . $e->getMessage());
                $this->warn('Check your mail configuration in .env');
            }

            // Verify OTP
            $this->line('Step 4: Verifying OTP...');
            if ($pending->verifyOtp($otp)) {
                $this->info('✅ OTP verified');
            } else {
                $this->error('❌ OTP verification failed');
                return 1;
            }

            // Create user
            $this->line('Step 5: Creating user account...');
            $user = $pending->createUser();
            if ($user) {
                $this->info('✅ User account created: ID ' . $user->id);
                $this->line('   Username: ' . $user->username);
                $this->line('   Approval Status: ' . $user->approval_status);
                $this->line('   Is Active: ' . ($user->is_active ? 'YES' : 'NO'));
            } else {
                $this->error('❌ User creation failed');
                return 1;
            }

            // Verify in database
            $this->line('Step 6: Verifying in database...');
            $dbUser = User::find($user->id);
            if ($dbUser) {
                $this->info('✅ User found in database');
            } else {
                $this->error('❌ User not found in database');
                return 1;
            }

            // Test login
            $this->line('Step 7: Testing login...');
            if (auth()->attempt(['username' => $user->username, 'password' => 'TestPass123!'])) {
                $this->info('✅ Login successful');
                auth()->logout();
            } else {
                $this->error('❌ Login failed');
            }

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            Log::error('Registration debug error', ['error' => $e->getMessage()]);
            return 1;
        }

        // ✅ FINAL SUMMARY
        $this->line('');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('✅ DIAGNOSTIC COMPLETE');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->warn('Check storage/logs/laravel.log for detailed logs');
        $this->warn('Test Email: ' . $testEmail);
        $this->warn('Test OTP: ' . $otp ?? 'N/A');
    }
}