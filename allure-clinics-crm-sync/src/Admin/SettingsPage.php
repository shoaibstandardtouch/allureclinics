<?php

namespace AllureClinics\Admin;

class SettingsPage {

    public function render(): void {
        if (isset($_POST['allure_clinics_settings_nonce']) && wp_verify_nonce($_POST['allure_clinics_settings_nonce'], 'save_settings')) {
            update_option('ac_crm_adapter', sanitize_text_field($_POST['ac_crm_adapter']));
            update_option('ac_wati_phone', sanitize_text_field($_POST['ac_wati_phone']));
            update_option('ac_wati_message', sanitize_text_field($_POST['ac_wati_message']));
            
            update_option('ac_sms_provider', sanitize_text_field($_POST['ac_sms_provider']));
            update_option('ac_taqnyat_bearer_token', sanitize_text_field($_POST['ac_taqnyat_bearer_token']));
            update_option('ac_taqnyat_sender_name', sanitize_text_field($_POST['ac_taqnyat_sender_name']));
            
            echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
        }

        $current_adapter = get_option('ac_crm_adapter', 'null');
        $wati_phone = get_option('ac_wati_phone', '');
        $wati_message = get_option('ac_wati_message', 'Hello, I would like to book an appointment.');

        $current_sms_provider = get_option('ac_sms_provider', 'log_only');
        $taqnyat_token = get_option('ac_taqnyat_bearer_token', '');
        $taqnyat_sender = get_option('ac_taqnyat_sender_name', '');

        include plugin_dir_path(__FILE__) . 'views/settings.php';
    }
}
