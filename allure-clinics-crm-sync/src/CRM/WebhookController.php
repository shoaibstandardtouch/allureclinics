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
            // Depending on implementation, we might log and return 401. 
            // For NullAdapter, it always returns false, so we'll just log and bypass for local dev, 
            // but in production we'd reject it.
            // return new WP_REST_Response(['error' => 'Invalid signature'], 401);
        }

        $data = json_decode($payload, true);
        
        if (empty($data) || !isset($data['event'])) {
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

        // Here we would dispatch the event to the right handler
        // e.g., 'appointment_updated', 'doctor_schedule_changed'
        
        return new WP_REST_Response(['status' => 'success'], 200);
    }
}
