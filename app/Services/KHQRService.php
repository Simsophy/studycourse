<?php // Opening PHP tag

namespace App\Services; // Define namespace for Service layer classes

/**
 * ============================================================================
 * [ SYSTEM MAIN FUNCTION ] : Dynamic KHQR Generation Engine
 * ============================================================================
 * 
 * CORE RESPONSIBILITY:
 * This service serves as the primary logic engine for the payment system.
 * It is responsible for transforming static merchant data into unique, 
 * transaction-specific EMVCo standard strings.
 */
class KHQRService // Define the service class
{
    /**
     * The main function to dynamically generate a KHQR code for a specific amount.
     * It parses the EMVCo TLV string, updates the amount (Tag 54), and recalculates CRC (Tag 63).
     */
    public function generateDynamicQR(string $baseQR, float $amount): string // Method signature with base string and amount
    {
        // 1. Remove existing CRC (last 4 characters of the EMVCo string)
        $payload = substr($baseQR, 0, -4); // Slice the string to remove the old checksum

        // 2. Parse and Update Amount (Tag 54)
        // EMVCo format for Tag 54 is "54" (tag) + length (2 digits) + value (amount)
        $amountStr = number_format($amount, 2, '.', ''); // Format amount to 2 decimal places (e.g., 10.00)
        $len = str_pad(strlen($amountStr), 2, '0', STR_PAD_LEFT); // Ensure length is 2 digits with leading zero
        $tag54 = "54" . $len . $amountStr; // Concatenate to form the full Tag 54 string

        // Replace or Inject Tag 54 into the payload
        if (str_contains($payload, "54")) { // If Tag 54 already exists in the base string...
            // Use regex to locate and replace the existing amount tag
            $payload = preg_replace('/54\d{2}[0-9.]+/', $tag54, $payload); // Replacement logic
        } else {
            // If No Tag 54 exists, append it before the CRC tag position
            $payload .= $tag54; // Append the new tag
        }

        // 3. Recalculate CRC16-CCITT (Tag 63)
        // The CRC is calculated over the entire payload plus the "6304" tag itself
        $crc = $this->calculateCRC16($payload . "6304"); // Call the CRC calculation helper

        // Return the final string: Payload + CRC tag + Hexadecimal result
        return $payload . "6304" . strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT)); // Final assembly
    }

    /**
     * CRC16-CCITT implementation (XModem version)
     * Standard checksum algorithm for EMVCo QR specifications.
     */
    private function calculateCRC16($data): int // Helper method for CRC calculation
    {
        $crc = 0xFFFF; // Initial value for CRC-CCITT
        for ($i = 0; $i < strlen($data); $i++) { // Iterate through each character in the string
            $crc ^= ord($data[$i]) << 8; // XOR the character byte into the top of the register
            for ($j = 0; $j < 8; $j++) { // Perform bitwise operations for each of the 8 bits
                if ($crc & 0x8000) { // If the most significant bit is 1...
                    $crc = ($crc << 1) ^ 0x1021; // Shift left and XOR with polynomial 0x1021
                } else {
                    $crc <<= 1; // Otherwise, just shift left
                }
            }
        }
        return $crc & 0xFFFF; // Return 16-bit result (masking any overflows)
    }
} // End of service class

