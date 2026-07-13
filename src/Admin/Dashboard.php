<?php

namespace AllureClinics\Admin;

class Dashboard {

    public function render(): void {
        global $wpdb;
        
        $today = current_time('Y-m-d');
        
        // Basic Stats
        $appointments_today = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(a.id) FROM {$wpdb->prefix}ac_appointments a 
             JOIN {$wpdb->prefix}ac_doctor_slots s ON a.slot_id = s.id 
             WHERE s.date = %s",
            $today
        ));

        $pending_sync = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}ac_appointments WHERE sync_status = 'pending_push'");
        $failed_sync = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}ac_appointments WHERE sync_status = 'sync_failed'");

        include plugin_dir_path(__FILE__) . 'views/dashboard.php';
    }
}
