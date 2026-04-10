<?php // Opening PHP tag

namespace App\Providers; // Define the namespace for Service Providers

use Illuminate\Auth\Middleware\RedirectIfAuthenticated; // Import middleware for auth redirection logic
use Illuminate\Auth\Notifications\ResetPassword; // Import password reset notification support
use Illuminate\Cache\RateLimiter; // Import rate limiting foundation
use Illuminate\Cache\RateLimiting\Limit; // Import specific limit definitions
use Illuminate\Foundation\Http\Middleware\ValidatePostSize; // Import size validation middleware
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider; // Import base ServiceProvider class
use Illuminate\Http\Request; // Import Request object
use Illuminate\Support\Facades\RateLimiter as LaravelRateLimiter; // Import RateLimiter facade
use Illuminate\Support\Facades\Route; // Import Route facade

class AppServiceProvider extends ServiceProvider // Define the application service provider
{
    /**
     * Register any application services.
     */
    public function register(): void // Method for global container binding
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void // Method for service initialization
    {
        // Define rate limiters to prevent brute-force attacks on sensitive endpoints
        LaravelRateLimiter::for('auth-login', function (Request $request) { // Rate limiter for login
            return Limit::perMinute(5)->by($request->ip()); // Allow 5 attempts per minute per IP address
        });

        LaravelRateLimiter::for('auth-register', function (Request $request) { // Rate limiter for registration
            return Limit::perMinute(5)->by($request->ip()); // Allow 5 attempts per minute per IP address
        });

        LaravelRateLimiter::for('auth-password-reset', function (Request $request) { // Rate limiter for password resets
            return Limit::perMinute(5)->by($request->ip()); // Allow 5 attempts per minute per IP address
        });

        // Configuration: Define where users are redirected if they are already logged in but visit a guest-only page
        RedirectIfAuthenticated::redirectUsing(function ($request) { // Custom logic for auth redirection
            $routeName = $request->route()?->getName(); // Get the current target route name

            // If an authenticated Admin visits the admin login page...
            if ($routeName === 'admin.login' || $request->is('admin') || $request->is('admin/*')) { 
                if (\Illuminate\Support\Facades\Route::has('admin.dashboard')) { // If dashboard route exists...
                    return route('admin.dashboard'); // Redirect Admin to their management dashboard
                }
            }

            // If an authenticated Student (User) visits student login or register...
            if ($routeName === 'login' || $routeName === 'register' || $request->is('user') || $request->is('user/*')) { 
                if (\Illuminate\Support\Facades\Route::has('user.dashboard')) { // If student dashboard route exists...
                    return route('user.dashboard'); // Redirect Student to their learning dashboard
                }
            }

            // Try to find a default dashboard or home route for any other user type
            foreach (['dashboard', 'home'] as $uri) { 
                if (\Illuminate\Support\Facades\Route::has($uri)) { 
                    return route($uri); 
                }
            }

            return '/'; // Final fallback to system root
        });

        // Define authorization gates to control access to specific application features
        \Illuminate\Support\Facades\Gate::define('access-admin-panel', function (\App\Models\Admin $admin) { 
            // Only allow designated roles to enter the admin back-office
            return in_array($admin->role, ['admin', 'developer']); 
        });

        \Illuminate\Support\Facades\Gate::define('access-student-area', function (\App\Models\User $user) { 
            // All verified students can access the frontend student dashboard
            return true; 
        });

        \Illuminate\Support\Facades\Gate::define('manage-users', function (\App\Models\Admin $admin) { 
            // Only high-level administrative roles can manage the user directory
            return in_array($admin->role, ['admin', 'developer']); 
        });
    }
} // End of class

