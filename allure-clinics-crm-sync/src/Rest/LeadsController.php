<?php

namespace AllureClinics\Rest;

use WP_REST_Request;
use WP_REST_Response;
use AllureClinics\Notifications\EmailNotifier;

class LeadsController {

    private EmailNotifier $emailNotifier;

    public function __construct(EmailNotifier $emailNotifier = null) {
        $this->emailNotifier = $emailNotifier;
    }

    /**
     * Capture a new lead.
     * POST /allure/v1/leads
     */
    public function create_lead(WP_REST_Request $request): WP_REST_Response {
        $name = sanitize_text_field($request->get_param('name'));
        $mobile = sanitize_text_field($request->get_param('mobile'));
        $email = sanitize_email($request->get_param('email'));
        $service_interest = sanitize_text_field($request->get_param('service_interest'));
        $campaign_source = sanitize_text_field($request->get_param('campaign_source'));
        $message = sanitize_textarea_field($request->get_param('message'));

        if (empty($name) || empty($mobile)) {
            return new WP_REST_Response(['error' => 'Name and mobile number are required.'], 400);
        }

        global $wpdb;
        $inserted = $wpdb->insert(
            $wpdb->prefix . 'ac_leads',
            array(
                'name'             => $name,
                'mobile'           => $mobile,
                'email'            => $email,
                'service_interest' => $service_interest,
                'campaign_source'  => $campaign_source,
                'message'          => $message,
                'status'           => 'new',
                'created_at'       => current_time('mysql')
            )
        );

        if (!$inserted) {
            return new WP_REST_Response(['error' => 'Failed to capture lead. Please try again.'], 500);
        }

        // Send Email Notification
        if ($this->emailNotifier) {
            $this->emailNotifier->sendAdminLeadNotification([
                'name' => $name,
                'mobile' => $mobile,
                'email' => $email,
                'service_interest' => $service_interest,
                'campaign_source' => $campaign_source,
                'message' => $message
            ]);
        }

        return new WP_REST_Response([
            'status' => 'success',
            'message' => 'Thank you! Your request has been received. Our team will contact you shortly.',
            'lead_id' => $wpdb->insert_id
        ], 201);
    }
}
