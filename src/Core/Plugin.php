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
        $smsProvider = \AllureClinics\Auth\SmsProviderFactory::create();
        $otpService = new \AllureClinics\Auth\OtpService($smsProvider);
        $patientSession = new \AllureClinics\Auth\PatientSession();

        // CRM Sync
        $adapterFactory = new \AllureClinics\CRM\AdapterFactory();
        $syncManager = new \AllureClinics\CRM\SyncManager($adapterFactory);
        $webhookController = new \AllureClinics\CRM\WebhookController($syncManager);
        new \AllureClinics\Cron\SyncScheduler($syncManager);

        // Repos & Notifications
        $appointmentRepo = new \AllureClinics\Repositories\AppointmentRepository();
        $branchRepo = new \AllureClinics\Repositories\BranchRepository();
        $doctorRepo = new \AllureClinics\Repositories\DoctorRepository();
        $emailNotifier = new \AllureClinics\Notifications\EmailNotifier();

        // REST Controllers
        $appointmentsController = new \AllureClinics\Rest\AppointmentsController($appointmentRepo, $syncManager, $emailNotifier);
        $doctorsController = new \AllureClinics\Rest\DoctorsController();
        $patientAuthController = new \AllureClinics\Rest\PatientAuthController($otpService, $patientSession);
        $patientPortalController = new \AllureClinics\Rest\PatientPortalController($patientSession, $syncManager);
        $leadsController = new \AllureClinics\Rest\LeadsController(new \AllureClinics\Repositories\LeadRepository());
        $webhookController = new \AllureClinics\CRM\WebhookController($syncManager);
        $branchesController = new \AllureClinics\Rest\BranchesController($branchRepo);

        $restRegistrar = new \AllureClinics\Core\RestRegistrar(
            $appointmentsController,
            $doctorsController,
            $patientAuthController,
            $patientPortalController,
            $leadsController,
            $webhookController,
            $branchesController
        );

        // Admin
        if (is_admin()) {
            $dashboard = new \AllureClinics\Admin\Dashboard();
            $settingsPage = new \AllureClinics\Admin\SettingsPage();
            $getStartedPage = new \AllureClinics\Admin\GetStartedPage();
            $leadsPage = new \AllureClinics\Admin\LeadsPage();
            $branchesPage = new \AllureClinics\Admin\BranchesPage($branchRepo);
            $doctorsPage = new \AllureClinics\Admin\DoctorsPage($doctorRepo, $branchRepo);
            
            new \AllureClinics\Admin\AdminMenu(
                $dashboard, 
                $settingsPage, 
                $getStartedPage, 
                $leadsPage,
                $branchesPage,
                $doctorsPage
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
        new \AllureClinics\Frontend\ShortcodeBookingWidget();
        new \AllureClinics\Frontend\ShortcodeLeadForm();
        new \AllureClinics\Frontend\ShortcodePatientPortal();
        new \AllureClinics\Frontend\WatiClickToChat();
        
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
