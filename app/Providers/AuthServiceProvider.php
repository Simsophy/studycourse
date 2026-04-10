<?php // Opening PHP tag

namespace App\Providers; // Define the namespace for Service Providers

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider; // Import base AuthServiceProvider
use Illuminate\Support\Facades\Gate; // Import the Gate facade for authorization checks
use App\Models\Admin; // Import the Admin model for type-hinting in gates
use App\Models\User; // Import the User model for type-hinting in gates

class AuthServiceProvider extends ServiceProvider // Define the AuthServiceProvider class
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy', // Map models to their respective policy classes
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void // The boot method is called after all providers are registered
    {
        $this->registerPolicies(); // Automatically register any policies defined in the $policies array

        // Define admin panel access gate
        Gate::define('access-admin-panel', function (Admin $admin) { // Accepts an authenticated Admin instance
            // Allow both admin and developer roles to access the panel
            return in_array($admin->role, ['admin', 'developer']); // Returns true if role is allowed
        });

        // Define student area access gate
        Gate::define('access-student-area', function (User $user) { // Accepts an authenticated User (student) instance
            // All authenticated users can access student area
            return true; // Simple boolean return for access
        });

        // Define user management gate (Fix for 403 error on /admin/users)
        Gate::define('manage-users', function (Admin $admin) { // Specifically for managing the student list
            // Only admin and developer roles can perform user management tasks
            return in_array($admin->role, ['admin', 'developer']); // Enforce role restriction
        });
    }
} // End of class

