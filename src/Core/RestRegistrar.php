<?php

namespace AllureClinics\Core;

use AllureClinics\Rest\AppointmentsController;
use AllureClinics\Rest\DoctorsController;
use AllureClinics\Rest\PatientAuthController;
use AllureClinics\Rest\PatientPortalController;
use AllureClinics\Rest\LeadsController;
use AllureClinics\CRM\WebhookController;
use AllureClinics\Rest\BranchesController;

class RestRegistrar {

    private ?AppointmentsController $appointmentsController;
    private ?DoctorsController $doctorsController;
    private ?PatientAuthController $authController;
    private ?PatientPortalController $portalController;
    private LeadsController $leadsController;
    private WebhookController $webhookController;
    private ?BranchesController $branchesController;

    public function __construct(
        ?AppointmentsController $appointmentsController,
        ?DoctorsController $doctorsController,
        ?PatientAuthController $authController,
        ?PatientPortalController $portalController,
        LeadsController $leadsController,
        WebhookController $webhookController,
        ?BranchesController $branchesController = null
    ) {
        $this->appointmentsController = $appointmentsController;
        $this->doctorsController = $doctorsController;
        $this->authController = $authController;
        $this->portalController = $portalController;
        $this->leadsController = $leadsController;
        $this->webhookController = $webhookController;
        $this->branchesController = $branchesController;
        
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        $namespace = 'allure/v1';

        // Branches (Public)
        if ($this->branchesController) {
            register_rest_route($namespace, '/branches', [
                'methods' => 'GET',
                'callback' => [$this->branchesController, 'get_branches'],
                'permission_callback' => '__return_true'
            ]);
        }

        // Doctors & Slots (Public)
        if ($this->doctorsController) {
            register_rest_route($namespace, '/doctors', [
                'methods' => 'GET',
                'callback' => [$this->doctorsController, 'get_doctors'],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route($namespace, '/doctors/(?P<id>\d+)/schedule', [
                'methods' => 'GET',
                'callback' => [$this->doctorsController, 'get_doctor_schedule'],
                'permission_callback' => '__return_true'
            ]);
        }

        // Appointments (Public booking)
        if ($this->appointmentsController) {
            register_rest_route($namespace, '/appointments', [
                'methods' => 'POST',
                'callback' => [$this->appointmentsController, 'create_appointment'],
                'permission_callback' => '__return_true'
            ]);
        }

        // Auth
        if ($this->authController) {
            register_rest_route($namespace, '/auth/otp/request', [
                'methods' => 'POST',
                'callback' => [$this->authController, 'request_otp'],
                'permission_callback' => '__return_true'
            ]);

            register_rest_route($namespace, '/auth/otp/verify', [
                'methods' => 'POST',
                'callback' => [$this->authController, 'verify_otp'],
                'permission_callback' => '__return_true'
            ]);
        }

        // Patient Portal (Requires Bearer token)
        if ($this->portalController) {
            register_rest_route($namespace, '/patient/profile', [
                'methods' => 'GET',
                'callback' => [$this->portalController, 'get_profile'],
                'permission_callback' => '__return_true' // Authorization is checked in the controller
            ]);

            register_rest_route($namespace, '/patient/appointments', [
                'methods' => 'GET',
                'callback' => [$this->portalController, 'get_appointments'],
                'permission_callback' => '__return_true'
            ]);
        }

        // CRM Webhook
        register_rest_route($namespace, '/webhook/crm', [
            'methods' => 'POST',
            'callback' => [$this->webhookController, 'handle_webhook'],
            'permission_callback' => '__return_true'
        ]);

        // Leads API
        register_rest_route($namespace, '/leads', [
            'methods' => 'POST',
            'callback' => [$this->leadsController, 'create_lead'],
            'permission_callback' => '__return_true'
        ]);
    }
}
