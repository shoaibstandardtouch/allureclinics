<?php

namespace AllureClinics\Admin;

class GetStartedPage {

    public function render(): void {
        if (isset($_POST['allure_clinics_demo_nonce']) && wp_verify_nonce($_POST['allure_clinics_demo_nonce'], 'demo_action')) {
            if (current_user_can('manage_options')) {
                if (isset($_POST['load_demo_data'])) {
                    DemoSeeder::seed();
                    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Demo data loaded successfully.', 'allure-clinics') . '</p></div>';
                } elseif (isset($_POST['clear_demo_data'])) {
                    DemoSeeder::clear();
                    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Demo data cleared successfully.', 'allure-clinics') . '</p></div>';
                }
            }
        }

        include plugin_dir_path(__FILE__) . 'views/get-started.php';
    }
}
