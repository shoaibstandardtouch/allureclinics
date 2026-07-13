<?php

namespace AllureClinics\Auth;

interface SmsProviderInterface {
    
    /**
     * Send an SMS message to a mobile number.
     *
     * @param string $mobileNumber The recipient's mobile number.
     * @param string $message The message body (e.g. OTP text).
     * @return bool True on success, false on failure.
     */
    public function sendSms(string $mobileNumber, string $message): bool;
}
