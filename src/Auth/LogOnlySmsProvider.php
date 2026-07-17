<?php

namespace AllureClinics\Auth;

class LogOnlySmsProvider implements SmsProviderInterface {
    
    /**
     * "Sends" an SMS by writing it to the WordPress error log.
     */
    public function sendSms(string $mobileNumber, string $message): bool {
        // Extract the OTP code from the message string (assuming it's a 6-digit number)
        if (preg_match('/\b\d{6}\b/', $message, $matches)) {
            $otp_code = $matches[0];
            $normalized_mobile = ltrim($mobileNumber, '+');
            set_transient('ac_test_otp_' . $normalized_mobile, $otp_code, 300);
        }

        // In a real environment, you might log this to a custom table,
        // but error_log is safe and available everywhere for local testing.
        error_log(sprintf("[Allure Clinics SMS Simulator] To: %s | Message: %s", $mobileNumber, $message));
        
        return true;
    }
}
