<?php

namespace AllureClinics\CRM;

use AllureClinics\CRM\Adapters\NullAdapter;

class SyncManager {

    private AdapterFactory $adapterFactory;

    public function __construct(AdapterFactory $adapterFactory) {
        $this->adapterFactory = $adapterFactory;
    }

    /**
     * Get the currently active adapter.
     */
    public function getAdapter(): CrmAdapterInterface {
        return $this->adapterFactory->getActiveAdapter();
    }

    /**
     * Push a locally created appointment to the CRM.
     * Called immediately after a patient books on the website.
     */
    public function pushAppointment(int $localAppointmentId): bool {
        global $wpdb;
        $appointment = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ac_appointments WHERE id = %d", $localAppointmentId), ARRAY_A);
        
        if (!$appointment || $appointment['sync_status'] === 'synced') {
            return false;
        }

        // Ideally we would resolve patient CRM ID and doctor CRM ID here before pushing
        try {
            $result = $this->adapter->createAppointment($appointment);
            
            if (isset($result['crm_id'])) {
                $wpdb->update(
                    $wpdb->prefix . 'ac_appointments',
                    array(
                        'crm_id' => $result['crm_id'],
                        'sync_status' => 'synced',
                        'last_synced_at' => current_time('mysql')
                    ),
                    array('id' => $localAppointmentId)
                );
                return true;
            }
        } catch (\Exception $e) {
            // Log failure
            $wpdb->insert(
                $wpdb->prefix . 'ac_sync_log',
                array(
                    'entity_type' => 'appointment',
                    'entity_id' => $localAppointmentId,
                    'direction' => 'push',
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'created_at' => current_time('mysql')
                )
            );
            
            $wpdb->update(
                $wpdb->prefix . 'ac_appointments',
                array('sync_status' => 'sync_failed'),
                array('id' => $localAppointmentId)
            );
        }

        return false;
    }

    /**
     * Pull all doctors and schedules from CRM.
     * Normally called by SyncScheduler cron.
     */
    public function pullSchedules(): void {
        // Logic to retrieve from $this->adapter->getDoctors() and getDoctorSchedule()
        // and upsert into local tables.
        // Will be expanded in Step 3/5 when Repositories are built.
    }
}
