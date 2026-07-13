<?php

namespace AllureClinics\Cron;

use AllureClinics\CRM\SyncManager;

class SyncScheduler {

    private SyncManager $syncManager;

    public function __construct(SyncManager $syncManager) {
        $this->syncManager = $syncManager;
        
        add_action('allure_clinics_sync_cron', [$this, 'runPullSync']);
    }

    /**
     * Executes the periodic pull sync.
     */
    public function runPullSync(): void {
        global $wpdb;
        $adapter = $this->syncManager->getAdapter();

        try {
            // Retrieve all doctors from the CRM
            $doctors = $adapter->getDoctors();
            
            foreach ($doctors as $doctorData) {
                // Upsert doctor to wp_ac_doctors based on crm_id
                // (Implementation omitted for brevity, would be a DB UPSERT query)
                
                // Then fetch schedule for the next 30 days
                $today = current_time('Y-m-d');
                $nextMonth = date('Y-m-d', strtotime('+30 days', strtotime($today)));
                
                $slots = $adapter->getDoctorSchedule($doctorData['crm_id'], $today, $nextMonth);
                
                foreach ($slots as $slotData) {
                    // Upsert slots into wp_ac_doctor_slots
                }
            }

            // Log successful run
            $wpdb->insert(
                $wpdb->prefix . 'ac_sync_log',
                array(
                    'entity_type'      => 'scheduler',
                    'direction'        => 'pull',
                    'status'           => 'success',
                    'created_at'       => current_time('mysql')
                )
            );

        } catch (\Exception $e) {
            $wpdb->insert(
                $wpdb->prefix . 'ac_sync_log',
                array(
                    'entity_type'      => 'scheduler',
                    'direction'        => 'pull',
                    'status'           => 'failed',
                    'error_message'    => $e->getMessage(),
                    'created_at'       => current_time('mysql')
                )
            );
        }
    }
}
