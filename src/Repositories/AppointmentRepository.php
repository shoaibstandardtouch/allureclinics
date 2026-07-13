<?php

namespace AllureClinics\Repositories;

class AppointmentRepository {

    /**
     * Get a single appointment by ID.
     */
    public function getById(int $id): ?array {
        global $wpdb;
        $appointment = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ac_appointments WHERE id = %d", $id), ARRAY_A);
        return $appointment ?: null;
    }

    /**
     * Create a new appointment safely by locking the slot.
     */
    public function create(array $data): int|false {
        global $wpdb;

        // Extract slot ID
        $slot_id = $data['slot_id'] ?? 0;
        
        $wpdb->query("START TRANSACTION");

        // Lock the slot row to prevent race conditions
        $slot = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ac_doctor_slots WHERE id = %d FOR UPDATE", $slot_id), ARRAY_A);

        if (!$slot || $slot['status'] !== 'available') {
            $wpdb->query("ROLLBACK");
            return false;
        }

        // Slot is available, insert the appointment
        $wpdb->insert(
            $wpdb->prefix . 'ac_appointments',
            array(
                'patient_id'       => $data['patient_id'],
                'doctor_id'        => $data['doctor_id'],
                'branch_id'        => $data['branch_id'],
                'slot_id'          => $slot_id,
                'status'           => 'pending',
                'source'           => $data['source'] ?? 'website',
                'sync_status'      => 'pending_push',
                'created_at'       => current_time('mysql')
            )
        );

        $appointment_id = $wpdb->insert_id;

        if ($appointment_id) {
            // Mark slot as booked locally immediately
            $wpdb->update(
                $wpdb->prefix . 'ac_doctor_slots',
                array('status' => 'booked'),
                array('id' => $slot_id)
            );
            $wpdb->query("COMMIT");
            return $appointment_id;
        }

        $wpdb->query("ROLLBACK");
        return false;
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(int $id): bool {
        global $wpdb;
        $result = $wpdb->update(
            $wpdb->prefix . 'ac_appointments',
            array('status' => 'cancelled', 'sync_status' => 'pending_push'),
            array('id' => $id)
        );

        return $result !== false;
    }
}
