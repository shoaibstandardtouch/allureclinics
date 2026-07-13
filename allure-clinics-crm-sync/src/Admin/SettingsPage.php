<?php

namespace AllureClinics\Admin;

class SettingsPage {

    public function render(): void {
        if (isset($_POST['allure_clinics_settings_nonce']) && wp_verify_nonce($_POST['allure_clinics_settings_nonce'], 'save_settings')) {
            update_option('ac_crm_adapter', sanitize_text_field($_POST['ac_crm_adapter']));
            update_option('ac_wati_phone', sanitize_text_field($_POST['ac_wati_phone']));
            update_option('ac_wati_message', sanitize_text_field($_POST['ac_wati_message']));
            echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
        }

        $current_adapter = get_option('ac_crm_adapter', 'null');
        $wati_phone = get_option('ac_wati_phone', '');
        $wati_message = get_option('ac_wati_message', 'Hello, I would like to book an appointment.');

        include plugin_dir_path(__FILE__) . 'views/settings.php';
    }
}
