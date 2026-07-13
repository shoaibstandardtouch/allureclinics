<?php

namespace AllureClinics\Rest;

use WP_REST_Request;
use WP_REST_Response;
use AllureClinics\Repositories\AppointmentRepository;
use AllureClinics\CRM\SyncManager;
use AllureClinics\Notifications\EmailNotifier;

class AppointmentsController {

    private AppointmentRepository $repository;
    private SyncManager $syncManager;
    private EmailNotifier $emailNotifier;

    public function __construct(AppointmentRepository $repository, SyncManager $syncManager, EmailNotifier $emailNotifier) {
        $this->repository = $repository;
        $this->syncManager = $syncManager;
        $this->emailNotifier = $emailNotifier;
    }

    /**
     * Create an appointment (Booking flow)
     * POST /allure/v1/appointments
     */
    public function create_appointment(WP_REST_Request $request): WP_REST_Response {
        $patient_id = $request->get_param('patient_id'); // In module 2, this will be resolved via OTP session
        $doctor_id = $request->get_param('doctor_id');
        $branch_id = $request->get_param('branch_id');
        $slot_id = $request->get_param('slot_id');

        if (!$patient_id || !$doctor_id || !$branch_id || !$slot_id) {
            return new WP_REST_Response(['error' => 'Missing required fields'], 400);
        }

        $data = [
            'patient_id' => absint($patient_id),
            'doctor_id'  => absint($doctor_id),
            'branch_id'  => absint($branch_id),
            'slot_id'    => absint($slot_id),
            'source'     => 'website'
        ];

        $appointment_id = $this->repository->create($data);

        if (!$appointment_id) {
            return new WP_REST_Response(['error' => 'Slot is no longer available. Please choose another time.'], 409);
        }

        // Push to CRM immediately (do not block on failure)
        $this->syncManager->pushAppointment($appointment_id);

        // Send confirmation email
        $this->emailNotifier->sendAppointmentConfirmation($appointment_id);

        return new WP_REST_Response([
            'status' => 'success',
            'message' => 'Appointment booked successfully.',
            'appointment_id' => $appointment_id
        ], 201);
    }
}
