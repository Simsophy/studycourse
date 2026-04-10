<?php // Opening PHP tag

namespace App\Http\Controllers\Api; // Define namespace for API controllers

use App\Http\Controllers\Controller; // Import base Controller class
use App\Services\KHQRService; // Import the KHQR generation service
use Illuminate\Http\Request; // Import Request for handling API data
use Illuminate\Support\Facades\Log; // Import Log facade for error tracking

/**
 * ============================================================================
 * [ SYSTEM STRUCTURE ] : Payment API Gateway
 * ============================================================================
 */
class DynamicKHQRController extends Controller // Define the controller class
{
    protected $khqrService; // Protected property to hold the KHQR service instance

    // Constructor with Dependency Injection of KHQRService
    public function __construct(KHQRService $khqrService) // Accepts an instance of KHQRService
    {
        $this->khqrService = $khqrService; // Store the service in the class property
    }

    /**
     * API Endpoint to generate a dynamic payment QR.
     * This defines the core structure of the payment request flow.
     */
    public function generate(Request $request) // Method to handle QR generation requests
    {
        // Validate the incoming JSON request data
        $request->validate([ 
            'amount' => 'required|numeric|min:0.01', // Amount must be a positive number
            'order_id' => 'required|string' // Order ID is required for tracking
        ]);

        try {
            // In a real scenario, base QR would ideally come from private database or environment config
            // This is a static base merchant QR string following EMVCo standards
            $baseQR = "00020101021230500010012345678952045411530311654041.005802KH5908MERCHANT6005PHNOM6304ABCD";
            
            // Delegate the logic of generating the dynamic string to the KHQRService
            $dynamicQR = $this->khqrService->generateDynamicQR($baseQR, $request->amount); // Generate result

            // Return a structured JSON response to the client
            return response()->json([ 
                'status' => 'success', // Indicate successful completion
                'order_id' => $request->order_id, // Echo back the order ID
                'qr_string' => $dynamicQR, // Provide the generated QR string for the frontend to render
                'amount' => $request->amount, // Confirm the amount processed
                'timestamp' => now()->toIso8601String() // Include a standard ISO timestamp
            ]);

        } catch (\Exception $e) { // Catch any unexpected logical or service errors
            // Log the detailed error message for server-side debugging
            Log::error("KHQR Generation Failed: " . $e->getMessage()); 

            // Return a generic error message with 500 status to the client (hiding sensitive details)
            return response()->json([ 
                'status' => 'error',
                'message' => 'Failed to generate payment QR.'
            ], 500); // 500 Internal Server Error
        }
    }
} // End of controller class

