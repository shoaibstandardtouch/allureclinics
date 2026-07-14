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

        if (!class_exists('\Bookly\Lib\Entities\Customer')) {
            return new WP_REST_Response(['error' => 'Bookly not active'], 503);
        }

        $customer = \Bookly\Lib\Entities\Customer::find($patient_id);
        
        if (!$customer) {
            return new WP_REST_Response(['error' => 'Patient not found'], 404);
        }

        $info_fields = [];
        if ($customer->getInfoFields()) {
            $info_fields = json_decode($customer->getInfoFields(), true);
        }

        $profile = [
            'id' => $customer->getId(),
            'name' => $customer->getFullName(),
            'email' => $customer->getEmail(),
            'mobile_number' => $customer->getPhone(),
            'extra_info' => $info_fields
        ];

        return new WP_REST_Response($profile, 200);
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

        if (!class_exists('\Bookly\Lib\Entities\CustomerAppointment')) {
            return new WP_REST_Response(['error' => 'Bookly not active'], 503);
        }

        $records = \Bookly\Lib\Entities\CustomerAppointment::query('ca')
            ->select('
                ca.id,
                ca.status,
                a.start_date,
                a.end_date,
                st.full_name AS doctor_name,
                s.title AS service_name,
                l.name AS branch_name
            ')
            ->leftJoin('Appointment', 'a', 'a.id = ca.appointment_id')
            ->leftJoin('Staff', 'st', 'st.id = a.staff_id')
            ->leftJoin('Service', 's', 's.id = a.service_id')
            ->leftJoin('\BooklyLocations\Lib\Entities\Location', 'l', 'l.id = a.location_id')
            ->where('ca.customer_id', $patient_id)
            ->sortBy('a.start_date')
            ->order('DESC')
            ->fetchArray();

        $appointments = [];
        foreach ($records as $record) {
            $appointments[] = [
                'id' => $record['id'],
                'status' => $record['status'],
                'date' => date('Y-m-d', strtotime($record['start_date'])),
                'start_time' => date('H:i', strtotime($record['start_date'])),
                'end_time' => date('H:i', strtotime($record['end_date'])),
                'doctor_name' => $record['doctor_name'],
                'service_name' => $record['service_name'],
                'branch_name' => $record['branch_name']
            ];
        }

        return new WP_REST_Response($appointments, 200);
    }
}
