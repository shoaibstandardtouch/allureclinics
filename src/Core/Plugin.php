<?php

namespace AllureClinics\Core;

class Plugin {
    /**
     * The single instance of the class.
     */
    private static $instance = null;

    /**
     * Main Plugin Instance.
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize the core functionality.
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_services();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies.
     */
    private function load_dependencies() {
        // Dependencies are autoloaded via Composer.
    }

    /**
     * Initialize all services and dependencies.
     */
    private function init_services() {
        $is_bookly_active = class_exists('\Bookly\Lib\Entities\Appointment');
        
        // Auth / SMS
        $smsProvider = \AllureClinics\Auth\SmsProviderFactory::create();
        $otpService = new \AllureClinics\Auth\OtpService($smsProvider);
        $patientSession = new \AllureClinics\Auth\PatientSession();

        // CRM Sync Core
        $adapterFactory = new \AllureClinics\CRM\AdapterFactory();
        $syncManager = new \AllureClinics\CRM\SyncManager($adapterFactory);
        $webhookController = new \AllureClinics\CRM\WebhookController($syncManager);
        
        // General Services
        $emailNotifier = new \AllureClinics\Notifications\EmailNotifier();
        $leadsController = new \AllureClinics\Rest\LeadsController($emailNotifier);
        
        // Bookly Integrations
        if ($is_bookly_active) {
            new \AllureClinics\Bookly\BooklySyncScheduler($syncManager);
            new \AllureClinics\Bookly\BooklyReminderScheduler($smsProvider);
            $patientPortalController = new \AllureClinics\Rest\PatientPortalController($patientSession, $syncManager);
            $patientAuthController = new \AllureClinics\Rest\PatientAuthController($otpService, $patientSession);

            $restRegistrar = new \AllureClinics\Core\RestRegistrar(
                null, // Deprecated: appointments
                null, // Deprecated: doctors
                $patientAuthController,
                $patientPortalController,
                $leadsController,
                $webhookController,
                null // Deprecated: branches
            );
        } else {
            // Bookly missing notice
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error is-dismissible"><p><strong>Allure CRM:</strong> Bookly (base + Pro) not detected — Bookly sync features are disabled until it is active.</p></div>';
            });
            
            // Register what we can
            $restRegistrar = new \AllureClinics\Core\RestRegistrar(
                null, null, null, null,
                $leadsController,
                $webhookController,
                null
            );
        }

        // Admin
        if (is_admin()) {
            $dashboard = new \AllureClinics\Admin\Dashboard();
            $settingsPage = new \AllureClinics\Admin\SettingsPage();
            $getStartedPage = new \AllureClinics\Admin\GetStartedPage();
            $leadsPage = new \AllureClinics\Admin\LeadsPage();
            
            // Note: BranchesPage and DoctorsPage are deprecated and removed from menu
            new \AllureClinics\Admin\AdminMenu(
                $dashboard, 
                $settingsPage, 
                $getStartedPage, 
                $leadsPage,
                null, // Deprecated
                null  // Deprecated
            );
        }
    }

    /**
     * Register admin hooks.
     */
    private function define_admin_hooks() {
        // We will wire up the Admin module later.
    }

    /**
     * Register public hooks.
     */
    private function define_public_hooks() {
        // Instantiate Frontend Shortcodes & Widgets
        new \AllureClinics\Frontend\ShortcodeLeadForm();
        new \AllureClinics\Frontend\ShortcodePatientPortal();
        new \AllureClinics\Frontend\WatiClickToChat();
        
        // Deprecated: new \AllureClinics\Frontend\ShortcodeBookingWidget();
        
        // Add custom cron schedule
        add_filter( 'cron_schedules', array( $this, 'add_cron_intervals' ) );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     */
    public function run() {
        // Action and filter hooks can be added here or in their respective controllers.
    }

    /**
     * Add custom cron intervals.
     */
    public function add_cron_intervals( $schedules ) {
        $schedules['allure_15min'] = array(
            'interval' => 15 * MINUTE_IN_SECONDS,
            'display'  => __( 'Every 15 Minutes', 'allure-clinics' )
        );
        return $schedules;
    }
}
