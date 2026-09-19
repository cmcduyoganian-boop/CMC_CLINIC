<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\UserActivity;

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
