<?php

namespace AllureClinics\Auth;

class PatientSession {

    /**
     * Create a new session for a verified patient.
     */
    public function createSession(int $patientId): string {
        $token = bin2hex(random_bytes(32));
        $transient_key = 'wp_ac_session_' . $token;
        
        // Store patient ID in transient, valid for 24 hours
        set_transient($transient_key, $patientId, DAY_IN_SECONDS);
        
        return $token;
    }

    /**
     * Verify a session token and return the patient ID.
     */
    public function getPatientIdFromToken(string $token): ?int {
        $transient_key = 'wp_ac_session_' . sanitize_text_field($token);
        $patientId = get_transient($transient_key);
        
        return $patientId ? (int) $patientId : null;
    }

    /**
     * Destroy a session.
     */
    public function destroySession(string $token): void {
        $transient_key = 'wp_ac_session_' . sanitize_text_field($token);
        delete_transient($transient_key);
    }
}
