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
        // Auth / SMS
        $smsProvider = new \AllureClinics\Auth\LogOnlySmsProvider();
        $otpService = new \AllureClinics\Auth\OtpService($smsProvider);
        $patientSession = new \AllureClinics\Auth\PatientSession();

        // CRM Sync
        $syncManager = new \AllureClinics\CRM\SyncManager();
        $webhookController = new \AllureClinics\CRM\WebhookController($syncManager);
        new \AllureClinics\Cron\SyncScheduler($syncManager);

        // Repos & Notifications
        $appointmentRepo = new \AllureClinics\Repositories\AppointmentRepository();
        $emailNotifier = new \AllureClinics\Notifications\EmailNotifier();

        // REST Controllers
        $appointmentsController = new \AllureClinics\Rest\AppointmentsController($appointmentRepo, $syncManager, $emailNotifier);
        $doctorsController = new \AllureClinics\Rest\DoctorsController();
        $patientAuthController = new \AllureClinics\Rest\PatientAuthController($otpService, $patientSession);
        $patientPortalController = new \AllureClinics\Rest\PatientPortalController($patientSession, $syncManager);

        // REST Registrar
        new RestRegistrar(
            $appointmentsController,
            $doctorsController,
            $patientAuthController,
            $patientPortalController,
            $webhookController
        );

        // Admin
        if (is_admin()) {
            $dashboard = new \AllureClinics\Admin\Dashboard();
            $settingsPage = new \AllureClinics\Admin\SettingsPage();
            $getStartedPage = new \AllureClinics\Admin\GetStartedPage();
            new \AllureClinics\Admin\AdminMenu($dashboard, $settingsPage, $getStartedPage);
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
        // Add custom cron schedule
        add_filter( 'cron_schedules', array( $this, 'add_cron_intervals' ) );
        
        // We will wire up REST API and Shortcodes later.
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
