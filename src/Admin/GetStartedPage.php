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
                } elseif (isset($_POST['cleanup_duplicate_leads'])) {
                    $deleted = $this->cleanupDuplicateLeads();
                    echo '<div class="notice notice-success is-dismissible"><p>' . sprintf(esc_html__('Cleanup complete: %d duplicate legacy leads removed.', 'allure-clinics'), $deleted) . '</p></div>';
                }
            }
        }

        // Calculate checklist statuses
        $bookly_core_active = class_exists('\Bookly\Lib\Plugin');
        $bookly_pro_active = class_exists('\BooklyPro\Lib\Plugin');
        $locations_active = class_exists('\BooklyLocations\Lib\Plugin');
        $waiting_list_active = class_exists('\BooklyWaitingList\Lib\Plugin');
        $custom_fields_active = class_exists('\BooklyCustomFields\Lib\Plugin');
        $customer_info_active = class_exists('\BooklyCustomerInformation\Lib\Plugin');
        $custom_statuses_active = class_exists('\BooklyCustomStatuses\Lib\Plugin');
        
        $addons_active = $locations_active && $waiting_list_active && $custom_fields_active && $customer_info_active && $custom_statuses_active;

        $staff_count = class_exists('\Bookly\Lib\Entities\Staff') ? \Bookly\Lib\Entities\Staff::query()->count() : 0;
        $location_count = class_exists('\BooklyLocations\Lib\Entities\Location') ? \BooklyLocations\Lib\Entities\Location::query()->count() : 0;
        
        $has_staff_and_location = ($staff_count > 0 && $location_count > 0);

        $taqnyat_bearer = get_option('allure_clinics_taqnyat_bearer');
        $taqnyat_sender = get_option('allure_clinics_taqnyat_sender');
        $has_sms_creds = (!empty($taqnyat_bearer) && !empty($taqnyat_sender));

        $book_page = get_page_by_path('book-appointment');
        $portal_page = get_page_by_path('patient-portal');
        $contact_page = get_page_by_path('contact-us');
        $has_demo_pages = ($book_page && $portal_page && $contact_page);

        // Check for duplicate legacy leads
        global $wpdb;
        $duplicate_count = $wpdb->get_var("
            SELECT COUNT(*) FROM (
                SELECT name, mobile, email, service_interest, message
                FROM {$wpdb->prefix}ac_leads
                GROUP BY name, mobile, email, service_interest, message
                HAVING COUNT(*) > 1
            ) as dupes
        ");
        $has_duplicates = ($duplicate_count > 0);

        include plugin_dir_path(__FILE__) . 'views/get-started.php';
    }

    private function createDemoPages(): void {
        $pages = [
            [
                'post_title'   => 'Book Appointment',
                'post_name'    => 'book-appointment',
                'post_content' => '<!-- wp:shortcode -->[bookly-form]<!-- /wp:shortcode -->',
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
            } else {
                // Repair path: if the Book Appointment page exists but has the old shortcode, update it.
                if ($page['post_name'] === 'book-appointment' && strpos($existing->post_content, '[allure_booking]') !== false) {
                    $existing->post_content = str_replace('[allure_booking]', '[bookly-form]', $existing->post_content);
                    wp_update_post($existing);
                }
            }
        }
    }

    private function cleanupDuplicateLeads(): int {
        global $wpdb;
        $deleted_count = 0;

        // Find groups of duplicates
        $duplicates = $wpdb->get_results("
            SELECT name, mobile, email, service_interest, message
            FROM {$wpdb->prefix}ac_leads
            GROUP BY name, mobile, email, service_interest, message
            HAVING COUNT(*) > 1
        ", ARRAY_A);

        foreach ($duplicates as $group) {
            // Find all IDs in this group, ordered by created_at ASC
            $query = $wpdb->prepare("
                SELECT id 
                FROM {$wpdb->prefix}ac_leads 
                WHERE name = %s AND mobile = %s AND email = %s AND service_interest = %s AND message = %s
                ORDER BY created_at ASC
            ", $group['name'], $group['mobile'], $group['email'], $group['service_interest'], $group['message']);
            
            $ids = $wpdb->get_col($query);

            if (count($ids) > 1) {
                // Keep the first one, delete the rest
                array_shift($ids);
                $ids_list = implode(',', array_map('intval', $ids));
                
                $wpdb->query("DELETE FROM {$wpdb->prefix}ac_leads WHERE id IN ($ids_list)");
                $deleted_count += count($ids);
            }
        }

        return $deleted_count;
    }
}
