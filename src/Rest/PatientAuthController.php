<?php

namespace AllureClinics\Rest;

use WP_REST_Request;
use WP_REST_Response;
use AllureClinics\Auth\OtpService;
use AllureClinics\Auth\PatientSession;

class PatientAuthController {

    private OtpService $otpService;
    private PatientSession $patientSession;

    public function __construct(OtpService $otpService, PatientSession $patientSession) {
        $this->otpService = $otpService;
        $this->patientSession = $patientSession;
    }

    /**
     * Request OTP
     * POST /allure/v1/auth/otp/request
     */
    public function request_otp(WP_REST_Request $request): WP_REST_Response {
        $mobile = sanitize_text_field($request->get_param('mobile_number'));
        
        if (empty($mobile)) {
            return new WP_REST_Response(['error' => 'Mobile number is required'], 400);
        }

        $result = $this->otpService->requestOtp($mobile);

        if ($result['status'] === 'error') {
            return new WP_REST_Response(['error' => $result['message']], 429);
        }

        return new WP_REST_Response(['message' => $result['message']], 200);
    }

    /**
     * Verify OTP
     * POST /allure/v1/auth/otp/verify
     */
    public function verify_otp(WP_REST_Request $request): WP_REST_Response {
        $mobile = sanitize_text_field($request->get_param('mobile_number'));
        $otp = sanitize_text_field($request->get_param('otp'));

        if (empty($mobile) || empty($otp)) {
            return new WP_REST_Response(['error' => 'Mobile number and OTP are required'], 400);
        }

        $result = $this->otpService->verifyOtp($mobile, $otp);

        if ($result['status'] === 'error') {
            return new WP_REST_Response(['error' => $result['message']], 401);
        }

        $token = $this->patientSession->createSession($result['patient_id']);

        return new WP_REST_Response([
            'message' => 'Verified successfully',
            'session_token' => $token
        ], 200);
    }
}
