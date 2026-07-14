<?php

namespace AllureClinics\CRM;

use WP_REST_Request;
use WP_REST_Response;

class WebhookController {

    private SyncManager $syncManager;

    public function __construct(SyncManager $syncManager) {
        $this->syncManager = $syncManager;
    }

    /**
     * Handle incoming webhooks from the external CRM.
     * Endpoint: POST /allure/v1/webhook/crm
     */
    public function handle_webhook(WP_REST_Request $request): WP_REST_Response {
        $adapter = $this->syncManager->getAdapter();
        
        $payload = $request->get_body();
        $signature = $request->get_header('x-crm-signature') ?: '';

        // Verify authenticity if the adapter supports it
        if (!$adapter->verifyWebhookSignature($payload, $signature)) {
            // return new WP_REST_Response(['error' => 'Invalid signature'], 401);
        }

        $data = json_decode($payload, true);
        
        if (empty($data) || !isset($data['event']) || !isset($data['data']['source_id'])) {
            return new WP_REST_Response(['error' => 'Invalid payload'], 400);
        }

        global $wpdb;

        // Log the webhook reception
        $wpdb->insert(
            $wpdb->prefix . 'ac_sync_log',
            array(
                'entity_type'      => 'webhook',
                'entity_id'        => $data['event'],
                'direction'        => 'pull',
                'status'           => 'received',
                'payload_snapshot' => $payload,
                'created_at'       => current_time('mysql')
            )
        );

        if (!class_exists('\Bookly\Lib\Entities\CustomerAppointment')) {
            return new WP_REST_Response(['error' => 'Bookly not active'], 503);
        }

        $ca_id = intval($data['data']['source_id']);
        
        try {
            $customerAppointment = \Bookly\Lib\Entities\CustomerAppointment::find($ca_id);
            if (!$customerAppointment) {
                return new WP_REST_Response(['error' => 'Appointment not found'], 404);
            }

            // Set transient for loop prevention (15 seconds TTL)
            set_transient('ac_bookly_crm_update_' . $ca_id, true, 15);

            if (isset($data['data']['status'])) {
                $customerAppointment->setStatus($data['data']['status']);
            }
            if (isset($data['data']['notes'])) {
                $customerAppointment->setNotes($data['data']['notes']);
            }
            
            // Advance status_changed_at so Bookly knows it changed (the poller will skip it because of the transient)
            $customerAppointment->setStatusChangedAt(current_time('mysql'));
            $customerAppointment->save();

        } catch (\Exception $e) {
            return new WP_REST_Response(['error' => $e->getMessage()], 500);
        }
        
        return new WP_REST_Response(['status' => 'success'], 200);
    }
}
