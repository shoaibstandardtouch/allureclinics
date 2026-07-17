<?php

namespace AllureClinics\CRM\Adapters;

use AllureClinics\CRM\CrmAdapterInterface;

class NullAdapter implements CrmAdapterInterface {

    private function log(string $action, $data = null) {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'ac_sync_log',
            array(
                'entity_type'      => 'NullAdapter',
                'entity_id'        => null,
                'direction'        => 'push',
                'status'           => 'logged',
                'payload_snapshot' => json_encode(['action' => $action, 'data' => $data]),
                'error_message'    => 'CRM not configured. Action intercepted by NullAdapter.',
                'created_at'       => current_time('mysql')
            )
        );
    }

    public function getDoctors(?int $branchId = null): array {
        $this->log('getDoctors', ['branchId' => $branchId]);
        return []; // Null adapter returns no external doctors, relies entirely on local cache
    }

    public function getDoctorSchedule(string $crmDoctorId, string $dateFrom, string $dateTo): array {
        $this->log('getDoctorSchedule', ['crmDoctorId' => $crmDoctorId, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo]);
        return []; 
    }

    public function createAppointment(array $appointmentData): array {
        $this->log('createAppointment', $appointmentData);
        // Simulate success so local flow works
        return [
            'crm_id' => 'null_crm_appt_' . uniqid(),
            'status' => 'confirmed'
        ];
    }

    public function updateAppointment(string $crmAppointmentId, array $changes): array {
        $this->log('updateAppointment', ['crmAppointmentId' => $crmAppointmentId, 'changes' => $changes]);
        return ['status' => 'updated'];
    }

    public function cancelAppointment(string $crmAppointmentId, string $reason): bool {
        $this->log('cancelAppointment', ['crmAppointmentId' => $crmAppointmentId, 'reason' => $reason]);
        return true;
    }

    public function upsertPatient(array $patientData): array {
        $this->log('upsertPatient', $patientData);
        return [
            'crm_id' => 'null_crm_patient_' . uniqid()
        ];
    }

    public function getPatientInvoices(string $crmPatientId): array {
        // Do not log PII to local sync log. Just return empty array to simulate no invoices found.
        return [];
    }

    public function getPatientMedicalHistory(string $crmPatientId): array {
        // Do not log PII to local sync log. Return empty structured array.
        return [
            'consultations' => [],
            'treatments' => [],
            'visits' => [],
            'prescriptions' => [],
            'labs' => [],
            'documents' => []
        ];
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool {
        return false; // Webhooks don't apply to NullAdapter
    }
}
