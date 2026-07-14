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

// Native Autoloader (PSR-4 compliant)
spl_autoload_register(function ($class) {
    // The project's namespace prefix
    $prefix = 'AllureClinics\\';
    // Base directory for the namespace prefix
    $base_dir = ALLURE_CLINICS_PLUGIN_DIR . 'src/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // no, move to the next registered autoloader
    }

    // Get the relative class name
    $relative_class = substr($class, $len);
    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

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

add_action( 'plugins_loaded', 'run_allure_clinics_crm_sync' );
