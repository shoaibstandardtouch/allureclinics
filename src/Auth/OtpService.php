<?php

namespace AllureClinics\Auth;

class OtpService {

    private SmsProviderInterface $smsProvider;

    public function __construct(SmsProviderInterface $smsProvider) {
        $this->smsProvider = $smsProvider;
    }

    /**
     * Generate and send OTP.
     */
    public function requestOtp(string $mobileNumber): array {
        global $wpdb;
        
        // Rate limiting check: max 3 requests per 10 minutes
        $ten_mins_ago = gmdate('Y-m-d H:i:s', time() - 600);
        $recent_requests = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(id) FROM {$wpdb->prefix}ac_otp_requests WHERE mobile_number = %s AND created_at >= %s",
            $mobileNumber, $ten_mins_ago
        ));

        if ($recent_requests >= 3) {
            return ['status' => 'error', 'message' => 'Too many requests. Please try again later.'];
        }

        // Generate 6 digit OTP
        $otp = (string) wp_rand(100000, 999999);
        $otp_hash = wp_hash_password($otp);
        $expires_at = gmdate('Y-m-d H:i:s', time() + 300); // 5 minutes

        $wpdb->insert(
            $wpdb->prefix . 'ac_otp_requests',
            array(
                'mobile_number' => $mobileNumber,
                'otp_hash'      => $otp_hash,
                'expires_at'    => $expires_at,
                'created_at'    => current_time('mysql', true)
            )
        );

        $request_id = $wpdb->insert_id;

        // Send via SMS Provider
        $message = sprintf(__("Your Allure Clinics verification code is: %s. It expires in 5 minutes.", 'allure-clinics'), $otp);
        $this->smsProvider->sendSms($mobileNumber, $message);

        return [
            'status' => 'success',
            'message' => 'OTP sent successfully.',
            'request_id' => $request_id
        ];
    }

    /**
     * Verify OTP and return patient ID.
     */
    public function verifyOtp(string $mobileNumber, string $otp): array {
        global $wpdb;

        $request = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ac_otp_requests WHERE mobile_number = %s AND verified_at IS NULL ORDER BY id DESC LIMIT 1",
            $mobileNumber
        ), ARRAY_A);

        if (!$request) {
            return ['status' => 'error', 'message' => 'No pending OTP request found.'];
        }

        if ($request['attempts'] >= 5) {
            return ['status' => 'error', 'message' => 'Maximum attempts reached. Please request a new OTP.'];
        }

        if (strtotime($request['expires_at']) < time()) {
            return ['status' => 'error', 'message' => 'OTP has expired.'];
        }

        if (!wp_check_password($otp, $request['otp_hash'])) {
            $wpdb->update($wpdb->prefix . 'ac_otp_requests', ['attempts' => $request['attempts'] + 1], ['id' => $request['id']]);
            return ['status' => 'error', 'message' => 'Invalid OTP.'];
        }

        // Mark as verified
        $wpdb->update(
            $wpdb->prefix . 'ac_otp_requests',
            ['verified_at' => current_time('mysql', true)],
            ['id' => $request['id']]
        );

        // Find or create patient in Bookly
        $patient = clone \Bookly\Lib\Entities\Customer::query()
            ->where('phone', $mobileNumber)
            ->fetchRow();

        if (!$patient) {
            $customer = new \Bookly\Lib\Entities\Customer();
            $customer->setPhone($mobileNumber);
            $customer->setFullName('New Patient'); // Default, they can update it
            $customer->save();
            $patient_id = $customer->getId();
        } else {
            $patient_id = $patient['id'];
        }

        return ['status' => 'success', 'patient_id' => $patient_id];
    }
}
