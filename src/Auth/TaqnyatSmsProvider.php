<?php

namespace AllureClinics\Auth;

class TaqnyatSmsProvider implements SmsProviderInterface {
    
    private string $bearerToken;
    private string $senderName;

    public function __construct(string $bearerToken, string $senderName) {
        $this->bearerToken = $bearerToken;
        $this->senderName = $senderName;
    }

    public function sendSms(string $mobileNumber, string $message): bool {
        // Strip out leading + or 00
        $mobileNumber = preg_replace('/^(\+|00)/', '', $mobileNumber);

        $url = 'https://api.taqnyat.sa/v1/messages';

        $body = [
            'recipients' => [$mobileNumber],
            'body' => $message,
            'sender' => $this->senderName
        ];

        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'body' => wp_json_encode($body),
            'timeout' => 15
        ];

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            $this->logError('Taqnyat API Connection Error', null, $response->get_error_message());
            return false;
        }

        $statusCode = wp_remote_retrieve_response_code($response);
        $responseBody = wp_remote_retrieve_body($response);

        if ($statusCode >= 200 && $statusCode < 300) {
            return true;
        }

        $this->logError('Taqnyat API Error', $statusCode, $responseBody);
        return false;
    }

    private function logError(string $humanMessage, ?int $status, string $body): void {
        global $wpdb;
        
        $errorJson = wp_json_encode([
            'status' => $status,
            'body' => $body
        ]);

        $wpdb->insert(
            $wpdb->prefix . 'ac_sync_log',
            array(
                'entity_type'   => 'sms',
                'entity_id'     => '0',
                'direction'     => 'push',
                'status'        => 'failed',
                'error_message' => $humanMessage . ' | ' . $errorJson,
                'created_at'    => current_time('mysql', true)
            )
        );
    }
}
