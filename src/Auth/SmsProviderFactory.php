<?php

namespace AllureClinics\Auth;

class SmsProviderFactory {

    public static function getAvailableProviders(): array {
        $defaults = [
            'log_only' => LogOnlySmsProvider::class,
            'taqnyat'  => TaqnyatSmsProvider::class
        ];

        return apply_filters('allure_clinics_available_sms_providers', $defaults);
    }

    public static function create(): SmsProviderInterface {
        $selected = get_option('ac_sms_provider', 'log_only');
        $providers = self::getAvailableProviders();

        if ($selected === 'taqnyat') {
            $token = get_option('ac_taqnyat_bearer_token', '');
            $sender = get_option('ac_taqnyat_sender_name', '');

            if (empty($token) || empty($sender)) {
                // Fallback logic
                self::logFallback();
                return new LogOnlySmsProvider();
            }

            return new TaqnyatSmsProvider($token, $sender);
        }

        if (array_key_exists($selected, $providers)) {
            $class = $providers[$selected];
            // Only Taqnyat has custom constructor args so far, others default to empty
            if ($class === LogOnlySmsProvider::class) {
                return new LogOnlySmsProvider();
            }
            if (class_exists($class)) {
                return new $class();
            }
        }

        return new LogOnlySmsProvider();
    }

    private static function logFallback(): void {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'ac_sync_log',
            array(
                'entity_type'   => 'sms',
                'entity_id'     => '0',
                'direction'     => 'push',
                'status'        => 'info',
                'error_message' => 'Taqnyat selected but credentials are missing — currently falling back to log-only mode.',
                'created_at'    => current_time('mysql', true)
            )
        );
    }
}
