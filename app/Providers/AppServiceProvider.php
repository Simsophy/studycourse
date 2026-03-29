<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Course;
use App\Models\User;
use App\Policies\CoursePolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

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
        if (app()->environment('production') || filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOL)) {
            URL::forceScheme('https');
        }

        RateLimiter::for('auth-login', function (Request $request) {
            $identifier = (string) ($request->input('login') ?? $request->input('email') ?? 'unknown');

            return [
                Limit::perMinute(5)->by(strtolower($identifier).'|'.$request->ip()),
            ];
        });

        RateLimiter::for('auth-register', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
            ];
        });

        RateLimiter::for('auth-password-reset', function (Request $request) {
            $email = (string) ($request->input('email') ?? 'unknown');

            return [
                Limit::perMinute(3)->by(strtolower($email).'|'.$request->ip()),
            ];
        });

        $supportedLocales = ['en', 'kh'];
        $locale = session('locale');

        if (in_array($locale, $supportedLocales, true)) {
            App::setLocale($locale);
        }

        Gate::policy(Course::class, CoursePolicy::class);

        Gate::define('access-admin-panel', function ($actor): bool {
            return $actor instanceof Admin;
        });

        Gate::define('access-student-area', function ($actor): bool {
            return $actor instanceof User;
        });

        Gate::define('manage-users', function ($actor): bool {
            return $actor instanceof Admin;
        });

    }
}
