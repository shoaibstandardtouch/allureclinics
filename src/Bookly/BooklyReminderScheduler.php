<?php

namespace AllureClinics\Bookly;

use AllureClinics\Auth\SmsProviderInterface;
use Bookly\Lib\Entities\CustomerAppointment;

class BooklyReminderScheduler {

    private SmsProviderInterface $smsProvider;

    public function __construct(SmsProviderInterface $smsProvider) {
        $this->smsProvider = $smsProvider;
        
        add_action('allure_bookly_reminders_event', [$this, 'run_reminders']);
        
        if (!wp_next_scheduled('allure_bookly_reminders_event')) {
            wp_schedule_event(time(), 'allure_15min', 'allure_bookly_reminders_event');
        }
    }

    public function run_reminders() {
        if (!class_exists('\Bookly\Lib\Entities\Appointment')) {
            return;
        }

        $now = current_time('timestamp');
        $this->process_window($now, 24, '24h_reminder');
        $this->process_window($now, 2, '2h_reminder');
    }

    private function process_window(int $now, int $hours, string $reminder_type) {
        $target_time = $now + ($hours * 3600);
        $window_start = date('Y-m-d H:i:s', $target_time - 900); // -15 mins
        $window_end = date('Y-m-d H:i:s', $target_time + 900);   // +15 mins

        $records = CustomerAppointment::query('ca')
            ->select('
                ca.id AS ca_id,
                ca.appointment_id,
                a.start_date,
                c.phone AS customer_phone,
                c.full_name AS customer_name,
                st.full_name AS staff_name
            ')
            ->leftJoin('Appointment', 'a', 'a.id = ca.appointment_id')
            ->leftJoin('Customer', 'c', 'c.id = ca.customer_id')
            ->leftJoin('Staff', 'st', 'st.id = a.staff_id')
            ->where('ca.status', 'approved')
            ->whereBetween('a.start_date', $window_start, $window_end)
            ->fetchArray();

        global $wpdb;

        foreach ($records as $record) {
            $appointment_id = $record['appointment_id'];

            // Check if reminder was already sent
            $exists = $wpdb->get_var($wpdb->prepare("
                SELECT id FROM {$wpdb->prefix}ac_reminder_log 
                WHERE appointment_id = %d AND reminder_type = %s
            ", $appointment_id, $reminder_type));

            if ($exists) {
                continue; // Already sent
            }

            if (empty($record['customer_phone'])) {
                continue;
            }

            // Send SMS
            $message = sprintf(
                "Dear %s, a friendly reminder of your appointment with %s on %s. Please let us know if you need to reschedule.",
                $record['customer_name'],
                $record['staff_name'],
                date('M d, Y h:i A', strtotime($record['start_date']))
            );

            try {
                $this->smsProvider->sendSms($record['customer_phone'], $message);

                // Log it
                $wpdb->insert(
                    $wpdb->prefix . 'ac_reminder_log',
                    [
                        'appointment_id' => $appointment_id,
                        'reminder_type' => $reminder_type,
                        'sent_at' => current_time('mysql')
                    ]
                );
            } catch (\Exception $e) {
                error_log("Failed to send $reminder_type reminder for appointment $appointment_id: " . $e->getMessage());
            }
        }
    }
}
