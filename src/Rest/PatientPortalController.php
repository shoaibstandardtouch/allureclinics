<?php

namespace AllureClinics\Rest;

use WP_REST_Request;
use WP_REST_Response;
use AllureClinics\Auth\PatientSession;
use AllureClinics\CRM\SyncManager;

class PatientPortalController {

    private PatientSession $patientSession;
    private SyncManager $syncManager;

    public function __construct(PatientSession $patientSession, SyncManager $syncManager) {
        $this->patientSession = $patientSession;
        $this->syncManager = $syncManager;
    }

    /**
     * Middleware to check session token.
     */
    private function authenticate(WP_REST_Request $request): ?int {
        $auth_header = $request->get_header('authorization');
        if (!$auth_header || !preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
            return null;
        }

        $token = $matches[1];
        return $this->patientSession->getPatientIdFromToken($token);
    }

    /**
     * Get patient profile
     * GET /allure/v1/patient/profile
     */
    public function get_profile(WP_REST_Request $request): WP_REST_Response {
        $patient_id = $this->authenticate($request);
        if (!$patient_id) {
            return new WP_REST_Response(['error' => 'Unauthorized'], 401);
        }

        global $wpdb;
        $patient = $wpdb->get_row($wpdb->prepare("SELECT id, mobile_number, name, email, dob FROM {$wpdb->prefix}ac_patients WHERE id = %d", $patient_id), ARRAY_A);

        return new WP_REST_Response($patient, 200);
    }

    /**
     * Get patient appointments
     * GET /allure/v1/patient/appointments
     */
    public function get_appointments(WP_REST_Request $request): WP_REST_Response {
        $patient_id = $this->authenticate($request);
        if (!$patient_id) {
            return new WP_REST_Response(['error' => 'Unauthorized'], 401);
        }

        global $wpdb;
        $appointments = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT a.*, s.date, s.start_time, d.name as doctor_name, b.name as branch_name 
                 FROM {$wpdb->prefix}ac_appointments a
                 JOIN {$wpdb->prefix}ac_doctor_slots s ON a.slot_id = s.id
                 JOIN {$wpdb->prefix}ac_doctors d ON a.doctor_id = d.id
                 JOIN {$wpdb->prefix}ac_branches b ON a.branch_id = b.id
                 WHERE a.patient_id = %d ORDER BY s.date DESC",
                $patient_id
            ),
            ARRAY_A
        );

        return new WP_REST_Response($appointments, 200);
    }
}
