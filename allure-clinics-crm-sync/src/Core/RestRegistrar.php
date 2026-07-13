<?php

namespace AllureClinics\Core;

use AllureClinics\Rest\AppointmentsController;
use AllureClinics\Rest\DoctorsController;
use AllureClinics\Rest\PatientAuthController;
use AllureClinics\Rest\PatientPortalController;
use AllureClinics\CRM\WebhookController;

class RestRegistrar {

    private AppointmentsController $appointmentsController;
    private DoctorsController $doctorsController;
    private PatientAuthController $authController;
    private PatientPortalController $portalController;
    private WebhookController $webhookController;

    public function __construct(
        AppointmentsController $appointmentsController,
        DoctorsController $doctorsController,
        PatientAuthController $authController,
        PatientPortalController $portalController,
        WebhookController $webhookController
    ) {
        $this->appointmentsController = $appointmentsController;
        $this->doctorsController = $doctorsController;
        $this->authController = $authController;
        $this->portalController = $portalController;
        $this->webhookController = $webhookController;
        
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        $namespace = 'allure/v1';

        // Doctors & Slots (Public)
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

        // Appointments (Public booking)
        register_rest_route($namespace, '/appointments', [
            'methods' => 'POST',
            'callback' => [$this->appointmentsController, 'create_appointment'],
            'permission_callback' => '__return_true'
        ]);

        // Auth
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

        // Patient Portal (Requires Bearer token)
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

        // CRM Webhook
        register_rest_route($namespace, '/webhook/crm', [
            'methods' => 'POST',
            'callback' => [$this->webhookController, 'handle_webhook'],
            'permission_callback' => '__return_true'
        ]);
    }
}
