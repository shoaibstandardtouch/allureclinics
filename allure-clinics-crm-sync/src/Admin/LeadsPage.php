<?php

namespace AllureClinics\Admin;

class LeadsPage {

    public function render(): void {
        global $wpdb;

        // Handle simple status updates (e.g. mark as contacted)
        if (isset($_POST['update_lead_status']) && isset($_POST['lead_id']) && isset($_POST['new_status'])) {
            if (wp_verify_nonce($_POST['allure_clinics_leads_nonce'], 'update_lead')) {
                $wpdb->update(
                    $wpdb->prefix . 'ac_leads',
                    ['status' => sanitize_text_field($_POST['new_status'])],
                    ['id' => absint($_POST['lead_id'])]
                );
                echo '<div class="notice notice-success is-dismissible"><p>Lead status updated.</p></div>';
            }
        }

        // Fetch leads ordered by newest first
        $leads = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ac_leads ORDER BY created_at DESC", ARRAY_A);

        include plugin_dir_path(__FILE__) . 'views/leads.php';
    }
}
