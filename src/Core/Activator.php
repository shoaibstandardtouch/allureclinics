<?php

namespace AllureClinics\Core;

class Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     */
    public static function activate() {
        Installer::create_tables();
        
        // Schedule WP Cron events
        if ( ! wp_next_scheduled( 'allure_clinics_sync_cron' ) ) {
            wp_schedule_event( time(), 'allure_15min', 'allure_clinics_sync_cron' );
        }
        
        flush_rewrite_rules();
    }
}
