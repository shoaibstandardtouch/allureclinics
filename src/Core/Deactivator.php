<?php

namespace AllureClinics\Core;

class Deactivator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     */
    public static function deactivate() {
        // Clear WP Cron events
        wp_clear_scheduled_hook( 'allure_clinics_sync_cron' );
        
        flush_rewrite_rules();
    }
}
