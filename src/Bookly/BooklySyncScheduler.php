<?php

namespace AllureClinics\Bookly;

use AllureClinics\CRM\SyncManager;
use Bookly\Lib\Entities\CustomerAppointment;
use Bookly\Lib\Entities\Appointment;
use Bookly\Lib\Entities\Customer;
use Bookly\Lib\Entities\Staff;
use Bookly\Lib\Entities\Service;

class BooklySyncScheduler {

    private SyncManager $syncManager;

    public function __construct(SyncManager $syncManager) {
        $this->syncManager = $syncManager;
        
        add_action('allure_bookly_sync_event', [$this, 'run_sync']);
        
        if (!wp_next_scheduled('allure_bookly_sync_event')) {
            wp_schedule_event(time(), 'allure_15min', 'allure_bookly_sync_event');
        }
    }

    public function run_sync() {
        if (!class_exists('\Bookly\Lib\Entities\Appointment')) {
            return; // Safety check
        }

        $checkpoint = get_option('ac_bookly_last_sync_checkpoint', '1970-01-01 00:00:00');
        $max_changed_at = $checkpoint;

        try {
            // Use Bookly query builder
            $records = CustomerAppointment::query('ca')
                ->select('
                    ca.id AS ca_id,
                    ca.status,
                    ca.status_changed_at,
                    ca.notes,
                    ca.custom_fields,
                    a.start_date,
                    a.end_date,
                    c.full_name AS customer_name,
                    c.phone AS customer_phone,
                    c.email AS customer_email,
                    c.info_fields AS customer_info,
                    st.full_name AS staff_name,
                    s.title AS service_name,
                    l.name AS location_name
                ')
                ->leftJoin('Appointment', 'a', 'a.id = ca.appointment_id')
                ->leftJoin('Customer', 'c', 'c.id = ca.customer_id')
                ->leftJoin('Staff', 'st', 'st.id = a.staff_id')
                ->leftJoin('Service', 's', 's.id = a.service_id')
                ->leftJoin('\BooklyLocations\Lib\Entities\Location', 'l', 'l.id = a.location_id')
                ->whereGt('ca.status_changed_at', $checkpoint)
                ->sortBy('ca.status_changed_at')
                ->order('ASC')
                ->fetchArray();

            foreach ($records as $record) {
                // Skip if this change originated from CRM webhook (loop prevention)
                $transient_key = 'ac_bookly_crm_update_' . $record['ca_id'];
                if (get_transient($transient_key)) {
                    $max_changed_at = max($max_changed_at, $record['status_changed_at']);
                    continue;
                }

                // Decode JSON fields
                $custom_fields = !empty($record['custom_fields']) ? json_decode($record['custom_fields'], true) : [];
                $info_fields = !empty($record['customer_info']) ? json_decode($record['customer_info'], true) : [];

                // Map to CRM Payload
                $payload = [
                    'source_id' => $record['ca_id'],
                    'patient' => [
                        'name' => $record['customer_name'],
                        'phone' => $record['customer_phone'],
                        'email' => $record['customer_email'],
                        'extra_info' => $info_fields
                    ],
                    'appointment' => [
                        'date' => date('Y-m-d', strtotime($record['start_date'])),
                        'start_time' => date('H:i', strtotime($record['start_date'])),
                        'end_time' => date('H:i', strtotime($record['end_date'])),
                        'status' => $record['status'],
                        'notes' => $record['notes'],
                        'service' => $record['service_name'],
                        'doctor' => $record['staff_name'],
                        'branch' => $record['location_name'],
                        'custom_fields' => $custom_fields
                    ]
                ];

                $adapter = $this->syncManager->getAdapter();

                // Bookly status strings: pending, approved, cancelled, rejected, waitlisted, done
                // Plus any custom statuses
                $status = strtolower($record['status']);
                if (in_array($status, ['cancelled', 'rejected'])) {
                    $adapter->cancelAppointment($payload['source_id'], 'Status changed to ' . $status);
                } else {
                    // For approved, pending, waitlisted, done, or custom statuses
                    // we just send the update to the CRM (since it upserts)
                    $adapter->createAppointment($payload);
                }

                // Log it
                global $wpdb;
                $wpdb->insert(
                    $wpdb->prefix . 'ac_sync_log',
                    [
                        'entity_type' => 'bookly_sync_push',
                        'entity_id'   => $record['ca_id'],
                        'direction'   => 'push',
                        'status' => 'success',
                        'payload_snapshot' => json_encode($payload),
                        'created_at' => current_time('mysql')
                    ]
                );

                $max_changed_at = max($max_changed_at, $record['status_changed_at']);
            }

            // Only update checkpoint if we successfully processed the batch without throwing
            if ($max_changed_at > $checkpoint) {
                update_option('ac_bookly_last_sync_checkpoint', $max_changed_at);
            }

        } catch (\Exception $e) {
            global $wpdb;
            $wpdb->insert(
                $wpdb->prefix . 'ac_sync_log',
                [
                    'entity_type' => 'bookly_sync_push_error',
                    'direction'   => 'push',
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'created_at' => current_time('mysql')
                ]
            );
        }
    }
}
