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
                } elseif (isset($_POST['create_demo_pages'])) {
                    $this->createDemoPages();
                    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Demo pages created successfully.', 'allure-clinics') . '</p></div>';
                }
            }
        }

        include plugin_dir_path(__FILE__) . 'views/get-started.php';
    }

    private function createDemoPages(): void {
        $pages = [
            [
                'post_title'   => 'Book Appointment',
                'post_name'    => 'book-appointment',
                'post_content' => '<!-- wp:shortcode -->[allure_booking]<!-- /wp:shortcode -->',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ],
            [
                'post_title'   => 'Patient Portal',
                'post_name'    => 'patient-portal',
                'post_content' => '<!-- wp:shortcode -->[allure_patient_portal]<!-- /wp:shortcode -->',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ],
            [
                'post_title'   => 'Contact Us',
                'post_name'    => 'contact-us',
                'post_content' => '<!-- wp:shortcode -->[allure_lead_form service="General Inquiry"]<!-- /wp:shortcode -->',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ],
        ];

        foreach ($pages as $page) {
            $existing = get_page_by_path($page['post_name']);
            if (!$existing) {
                wp_insert_post($page);
            }
        }
    }
}
