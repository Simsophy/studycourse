<?php // Opening PHP tag

namespace App\Http\Middleware; // Define the namespace for Middleware classes

use Closure; // Import the Closure class for the callback
use Illuminate\Http\Request; // Import the Request object
use Symfony\Component\HttpFoundation\Response; // Import the base Response class

class IsAdmin // Define the middleware class
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next) // Main middleware logic
    {
        // Check if user is authenticated using the specific 'admin' guard session
        if (!auth('admin')->check()) { // If not logged in as admin...
            // Redirect the user to the admin login page
            return redirect()->route('admin.login'); 
        }

        // Retrieve the authenticated admin user instance
        $admin = auth('admin')->user(); 

        // Authorization check: Verify if the admin has an approved role
        if (!$admin || !in_array($admin->role, ['admin', 'developer'])) { // If user exists but role is unauthorized...
            
            // Check if the request expects an HTML response (browser navigation)
            if ($request->expectsHtml()) { 
                // Redirect back to login with a specific error message
                return redirect()->route('admin.login')->withErrors([ 
                    'login' => 'You do not have permission to access the admin panel.'
                ]);
            }

            // For non-HTML requests (like AJAX or API calls), return a structured JSON error
            return response()->json([ 
                'success' => false,
                'message' => 'Forbidden - Insufficient permissions',
            ], 403); // Return 403 Forbidden status
        }

        // If all checks pass, proceed to the next middleware or the controller
        return $next($request); 
    }
} // End of class

