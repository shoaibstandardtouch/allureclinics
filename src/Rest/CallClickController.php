<?php

namespace AllureClinics\Rest;

use WP_REST_Request;
use WP_REST_Response;

class CallClickController {

    /**
     * Log a call click
     * POST /allure/v1/call-click
     */
    public function log_click(WP_REST_Request $request): WP_REST_Response {
        global $wpdb;

        $source = sanitize_text_field($request->get_param('source'));
        if (empty($source)) {
            $source = 'website';
        }

        $page_url = esc_url_raw($request->get_param('page_url'));
        if (empty($page_url)) {
            $page_url = wp_get_referer() ?: '';
        }

        $branch_id = null;
        $branch_param = $request->get_param('branch_id');
        if (!empty($branch_param)) {
            $branch_id = intval($branch_param);
        }

        $wpdb->insert(
            $wpdb->prefix . 'ac_call_clicks',
            [
                'source' => $source,
                'page_url' => $page_url,
                'branch_id' => $branch_id,
                'created_at' => current_time('mysql')
            ],
            [
                '%s',
                '%s',
                $branch_id === null ? null : '%d',
                '%s'
            ]
        );

        return new WP_REST_Response(['status' => 'logged'], 200);
    }
}
