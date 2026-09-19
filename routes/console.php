<?php

use App\Console\Commands\SendAppointmentReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled command to send SMS appointment reminders daily at 8:00 AM
Schedule::command(SendAppointmentReminders::class)
    ->dailyAt('08:00')
    ->timezone('Asia/Manila')
    ->withoutOverlapping();
