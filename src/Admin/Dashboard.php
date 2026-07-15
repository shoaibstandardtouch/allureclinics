<?php

namespace AllureClinics\Admin;

class Dashboard {

    public function render(): void {
        global $wpdb;
        
        $today = current_time('Y-m-d');
        
        // System Status
        $bookly_active = class_exists('\Bookly\Lib\Entities\Appointment');
        $crm_adapter_name = get_option('allure_clinics_crm_adapter', 'null');
        $crm_status = ($crm_adapter_name === 'null') ? 'Not connected to a real CRM yet (NullAdapter)' : $crm_adapter_name;
        
        $last_sync_log = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ac_sync_log ORDER BY id DESC LIMIT 1", ARRAY_A);
        $last_sync_time = $last_sync_log ? $last_sync_log['created_at'] : 'Never';
        $last_sync_result = $last_sync_log ? $last_sync_log['status'] : 'N/A';
        
        $last_reminder_log = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}ac_reminder_log ORDER BY id DESC LIMIT 1", ARRAY_A);
        $last_reminder_time = $last_reminder_log ? $last_reminder_log['sent_at'] : 'Never';
        
        $demo_data_loaded = get_option('ac_demo_data_loaded', false);

        // Today's Appointments from Bookly
        $appointments_today = 0;
        if ($bookly_active) {
            $appointments_today = \Bookly\Lib\Entities\CustomerAppointment::query('ca')
                ->leftJoin('Appointment', 'a', 'a.id = ca.appointment_id')
                ->whereLike('a.start_date', $today . '%')
                ->count();
        }

        // Leads
        $total_leads = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}ac_leads");
        $new_leads = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}ac_leads WHERE status = 'new'");

        // Sync Logs
        $pending_sync = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}ac_sync_log WHERE status = 'pending'");
        $failed_sync = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}ac_sync_log WHERE status = 'failed'");

        include plugin_dir_path(__FILE__) . 'views/dashboard.php';
    }
}
