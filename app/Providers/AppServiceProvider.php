<?php

namespace App\Providers;

use App\Models\Patient;
use App\Models\ClinicVisit;
use App\Models\StudentHealthRecord;
use App\Models\User;
use App\Observers\UserObserver;
use App\Policies\PatientPolicy;
use App\Policies\ClinicVisitPolicy;
use App\Policies\StudentHealthRecordPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);

        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(ClinicVisit::class, ClinicVisitPolicy::class);
        Gate::policy(StudentHealthRecord::class, StudentHealthRecordPolicy::class);

        \Illuminate\Support\Facades\Event::listen(Login::class, function (Login $event) {
            UserActivity::create([
                'user_id' => $event->user->id,
                'action' => 'login',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'logged_in_at' => now(),
            ]);
        });

        \Illuminate\Support\Facades\Event::listen(Logout::class, function (Logout $event) {
            $activity = UserActivity::where('user_id', $event->user->id)
                ->whereNull('logged_out_at')
                ->orderByDesc('logged_in_at')
                ->first();

            if ($activity) {
                $activity->update([
                    'logged_out_at' => now(),
                ]);
            }
        });
    }
}
