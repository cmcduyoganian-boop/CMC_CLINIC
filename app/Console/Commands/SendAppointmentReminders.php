<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    protected $signature = 'app:send-appointment-reminders';
    protected $description = 'Send SMS reminders for appointments scheduled today';

    public function handle(SmsService $smsService): int
    {
        $today = now()->toDateString();

        $appointments = Appointment::with('patient')
            ->where('appointment_date', $today)
            ->where('status', 'scheduled')
            ->where('sms_reminder', true)
            ->where(function ($query) {
                $query->whereNull('sms_sent_at')
                      ->orWhere('sms_status', 'failed');
            })
            ->get();

        if ($appointments->isEmpty()) {
            $this->info("No appointments with SMS reminders scheduled for today ({$today}).");
            return self::SUCCESS;
        }

        $this->info("Found {$appointments->count()} appointment(s) with SMS reminders for today.");

        $bar = $this->output->createProgressBar($appointments->count());
        $bar->start();

        $sent = 0;
        $failed = 0;

        foreach ($appointments as $appointment) {
            $result = $smsService->sendAppointmentReminder($appointment);

            if ($result['success']) {
                $sent++;
                $this->line("<info>✓</info> Sent to {$appointment->patient->name} ({$appointment->patient->phone})");
            } else {
                $failed++;
                $this->line("<error>✗</error> Failed for {$appointment->patient->name}: " . ($result['error'] ?? 'Unknown error'));
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("SMS Summary for {$today}:");
        $this->table(
            ['Status', 'Count'],
            [
                ['Sent', $sent],
                ['Failed', $failed],
                ['Total', $appointments->count()],
            ]
        );

        Log::info('Daily SMS reminders sent', [
            'date' => $today,
            'total' => $appointments->count(),
            'sent' => $sent,
            'failed' => $failed,
        ]);

        return self::SUCCESS;
    }
}