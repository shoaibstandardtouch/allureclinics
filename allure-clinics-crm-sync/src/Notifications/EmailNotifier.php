<?php

namespace AllureClinics\Notifications;

class EmailNotifier {

    /**
     * Send booking confirmation email.
     */
    public function sendAppointmentConfirmation(int $appointmentId): bool {
        global $wpdb;
        
        $appointment = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ac_appointments WHERE id = %d", $appointmentId), ARRAY_A);
        if (!$appointment) return false;

        $patient = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ac_patients WHERE id = %d", $appointment['patient_id']), ARRAY_A);
        $doctor = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ac_doctors WHERE id = %d", $appointment['doctor_id']), ARRAY_A);
        $slot = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ac_doctor_slots WHERE id = %d", $appointment['slot_id']), ARRAY_A);
        $branch = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ac_branches WHERE id = %d", $appointment['branch_id']), ARRAY_A);

        if (!$patient || empty($patient['email'])) {
            return false; // Cannot send email if patient has no email
        }

        $to = $patient['email'];
        $subject = __('Your Appointment Confirmation - Allure Clinics', 'allure-clinics');

        // Extract template contents
        ob_start();
        include plugin_dir_path(__FILE__) . 'templates/appointment-confirmation.php';
        $message = ob_get_clean();

        $headers = array('Content-Type: text/html; charset=UTF-8');

        return wp_mail($to, $subject, $message, $headers);
    }
}
