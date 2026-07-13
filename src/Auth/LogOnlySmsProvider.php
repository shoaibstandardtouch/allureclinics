<?php

namespace AllureClinics\Auth;

class LogOnlySmsProvider implements SmsProviderInterface {
    
    /**
     * "Sends" an SMS by writing it to the WordPress error log.
     */
    public function sendSms(string $mobileNumber, string $message): bool {
        // In a real environment, you might log this to a custom table,
        // but error_log is safe and available everywhere for local testing.
        error_log(sprintf("[Allure Clinics SMS Simulator] To: %s | Message: %s", $mobileNumber, $message));
        
        return true;
    }
}
