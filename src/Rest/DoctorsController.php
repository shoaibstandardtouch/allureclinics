<?php

namespace AllureClinics\Rest;

use WP_REST_Request;
use WP_REST_Response;

class DoctorsController {

    /**
     * Get doctors list
     * GET /allure/v1/doctors
     */
    public function get_doctors(WP_REST_Request $request): WP_REST_Response {
        global $wpdb;
        $branch_id = $request->get_param('branch_id');
        
        $query = "SELECT * FROM {$wpdb->prefix}ac_doctors";
        if ($branch_id) {
            $query .= $wpdb->prepare(" WHERE branch_id = %d", absint($branch_id));
        }

        $doctors = $wpdb->get_results($query, ARRAY_A);
        
        return new WP_REST_Response($doctors, 200);
    }

    /**
     * Get doctor schedule / available slots
     * GET /allure/v1/doctors/{id}/schedule
     */
    public function get_doctor_schedule(WP_REST_Request $request): WP_REST_Response {
        global $wpdb;
        $doctor_id = $request->get_param('id');
        $date_from = $request->get_param('date_from') ?: current_time('Y-m-d');
        
        $slots = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}ac_doctor_slots WHERE doctor_id = %d AND date >= %s AND status = 'available' ORDER BY date ASC, start_time ASC",
                absint($doctor_id),
                $date_from
            ),
            ARRAY_A
        );

        return new WP_REST_Response($slots, 200);
    }
}
