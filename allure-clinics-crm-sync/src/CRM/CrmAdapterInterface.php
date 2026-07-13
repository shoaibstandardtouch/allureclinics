<?php

namespace AllureClinics\CRM;

interface CrmAdapterInterface {
    
    /**
     * Retrieve doctors from CRM.
     * 
     * @param int|null $branchId
     * @return array Array of doctor data
     */
    public function getDoctors(?int $branchId = null): array;

    /**
     * Retrieve doctor schedule.
     * 
     * @param string $crmDoctorId
     * @param string $dateFrom
     * @param string $dateTo
     * @return array Array of slots
     */
    public function getDoctorSchedule(string $crmDoctorId, string $dateFrom, string $dateTo): array;

    /**
     * Create appointment in CRM.
     * 
     * @param array $appointmentData
     * @return array ['crm_id' => ..., 'status' => ...]
     */
    public function createAppointment(array $appointmentData): array;

    /**
     * Update existing appointment in CRM.
     * 
     * @param string $crmAppointmentId
     * @param array $changes
     * @return array
     */
    public function updateAppointment(string $crmAppointmentId, array $changes): array;

    /**
     * Cancel an appointment in CRM.
     * 
     * @param string $crmAppointmentId
     * @param string $reason
     * @return bool True on success
     */
    public function cancelAppointment(string $crmAppointmentId, string $reason): bool;

    /**
     * Create or update patient in CRM.
     * 
     * @param array $patientData
     * @return array ['crm_id' => ...]
     */
    public function upsertPatient(array $patientData): array;

    /**
     * Retrieve patient records (consultations, prescriptions, invoices).
     * 
     * @param string $crmPatientId
     * @return array
     */
    public function getPatientRecords(string $crmPatientId): array;

    /**
     * Verify incoming webhook signature from CRM.
     * 
     * @param string $payload
     * @param string $signature
     * @return bool
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool;
}
