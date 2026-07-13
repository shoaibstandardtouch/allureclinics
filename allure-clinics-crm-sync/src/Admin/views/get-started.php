<div class="wrap">
    <h1><?php esc_html_e('Allure CRM Integration - Getting Started', 'allure-clinics'); ?></h1>
    <hr>
    
    <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); max-width: 800px; margin-top: 20px;">
        <h2><?php esc_html_e('Architecture Overview', 'allure-clinics'); ?></h2>
        <p><?php esc_html_e('This plugin is not a standalone CRM. It acts as a Sync/Integration layer between the WordPress booking portal and the external CRM.', 'allure-clinics'); ?></p>
        <p><strong><?php esc_html_e('Important concept:', 'allure-clinics'); ?></strong> <?php esc_html_e('The external CRM is the single source of truth. The local database tables (wp_ac_*) act purely as a fast cache for the website to read from.', 'allure-clinics'); ?></p>

        <h3><?php esc_html_e('Backend Team Documentation', 'allure-clinics'); ?></h3>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Component</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>CRM Adapter Interface</strong></td>
                    <td><code>src/CRM/CrmAdapterInterface.php</code> defines the contract for communicating with the CRM. To integrate a new CRM, implement this interface and register it.</td>
                </tr>
                <tr>
                    <td><strong>Null Adapter</strong></td>
                    <td>Currently active. A mock adapter that simulates a successful CRM connection and logs all outgoing requests to <code>wp_ac_sync_log</code> without sending real API calls.</td>
                </tr>
                <tr>
                    <td><strong>REST API Endpoints</strong></td>
                    <td>
                        <ul>
                            <li><code>GET /wp-json/allure/v1/doctors</code> - List doctors</li>
                            <li><code>GET /wp-json/allure/v1/doctors/{id}/schedule</code> - Get available slots</li>
                            <li><code>POST /wp-json/allure/v1/appointments</code> - Book slot (Pushes to CRM)</li>
                            <li><code>POST /wp-json/allure/v1/auth/otp/request</code> - Request OTP for Patient Login</li>
                            <li><code>POST /wp-json/allure/v1/auth/otp/verify</code> - Verify OTP & get Session Token</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><strong>Sync Webhooks</strong></td>
                    <td>Send CRM updates (like doctor schedule changes) to <code>POST /wp-json/allure/v1/webhook/crm</code>. The payload must include an <code>x-crm-signature</code> header.</td>
                </tr>
                <tr>
                    <td><strong>Cron Job (Fallback Sync)</strong></td>
                    <td>The <code>allure_clinics_sync_cron</code> WP Cron event runs every 15 minutes to pull the latest doctor schedules if webhooks fail.</td>
                </tr>
            </tbody>
        </table>

        <br>
        <p><em>For detailed code exploration, begin at <code>src/Core/Plugin.php</code>.</em></p>
    </div>
</div>
