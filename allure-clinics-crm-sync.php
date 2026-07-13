<?php
/**
 * Plugin Name:       Allure Clinics CRM Sync
 * Description:       CRM Sync/Integration layer and patient-facing booking portal.
 * Version:           1.0.0
 * Author:            StandardTouch e-Solutions
 * Text Domain:       allure-clinics
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define plugin constants
define( 'ALLURE_CLINICS_VERSION', '1.0.0' );
define( 'ALLURE_CLINICS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ALLURE_CLINICS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Require Composer autoloader
if ( file_exists( ALLURE_CLINICS_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
    require_once ALLURE_CLINICS_PLUGIN_DIR . 'vendor/autoload.php';
}

// The core plugin class that is used to define internationalization,
// admin-specific hooks, and public-facing site hooks.
use AllureClinics\Core\Plugin;
use AllureClinics\Core\Activator;
use AllureClinics\Core\Deactivator;

/**
 * The code that runs during plugin activation.
 */
function activate_allure_clinics_crm_sync() {
    // If the autoloader hasn't been generated, try to include the classes directly for activation
    if ( ! class_exists( 'AllureClinics\Core\Activator' ) ) {
        require_once ALLURE_CLINICS_PLUGIN_DIR . 'src/Core/Activator.php';
        require_once ALLURE_CLINICS_PLUGIN_DIR . 'src/Core/Installer.php';
    }
    Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_allure_clinics_crm_sync() {
    if ( ! class_exists( 'AllureClinics\Core\Deactivator' ) ) {
        require_once ALLURE_CLINICS_PLUGIN_DIR . 'src/Core/Deactivator.php';
    }
    Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_allure_clinics_crm_sync' );
register_deactivation_hook( __FILE__, 'deactivate_allure_clinics_crm_sync' );

/**
 * Begins execution of the plugin.
 */
function run_allure_clinics_crm_sync() {
    if ( class_exists( 'AllureClinics\Core\Plugin' ) ) {
        $plugin = Plugin::get_instance();
        $plugin->run();
    }
}

run_allure_clinics_crm_sync();
