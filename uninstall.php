<?php
/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Data retention: By default, we do not drop the tables to prevent accidental data loss.
// A setting could be added later to explicitly drop tables here.
