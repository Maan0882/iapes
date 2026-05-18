<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Policies\AttendancePolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        // Explicitly register the policy here
        Gate::policy(Attendance::class, AttendancePolicy::class);

        // Authentication Activity Logging
        \Illuminate\Support\Facades\Event::listen(
            [\Illuminate\Auth\Events\Login::class, \Illuminate\Auth\Events\Logout::class, \Illuminate\Auth\Events\Failed::class],
            [\App\Listeners\LogAuthenticationActivity::class, 'handle']
        );
    }
}
