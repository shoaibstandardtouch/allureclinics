<div class="wrap">
    <h1><?php esc_html_e('Allure CRM Integration - Getting Started', 'allure-clinics'); ?></h1>
    <hr>
    
    <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); max-width: 800px; margin-top: 20px;">
        <h2><?php esc_html_e('1. Setup Checklist', 'allure-clinics'); ?></h2>
        <ul style="font-size: 15px; line-height: 1.8;">
            <li>
                <?php echo $bookly_core_active && $bookly_pro_active ? '✅' : '☐'; ?> 
                <strong>Bookly & Bookly Pro active</strong>
            </li>
            <li>
                <?php echo $addons_active ? '✅' : '☐'; ?> 
                <strong>Locations, Waiting List, Custom Fields, Customer Information, Custom Statuses add-ons active</strong>
            </li>
            <li>
                <?php echo $has_staff_and_location ? '✅' : '☐'; ?> 
                <strong>At least one Bookly Staff (doctor) and one Location (branch) configured</strong>
            </li>
            <li>
                <?php echo $has_sms_creds ? '✅' : '☐'; ?> 
                <strong>Taqnyat SMS credentials configured</strong> 
                <?php if (!$has_sms_creds) echo '<em>(Currently using log-only fallback)</em>'; ?>
            </li>
            <li>
                <?php echo $has_demo_pages ? '✅' : '☐'; ?> 
                <strong>Demo pages created</strong>
            </li>
        </ul>
    </div>

    <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); max-width: 800px; margin-top: 20px;">
        <h2><?php esc_html_e('2. Demo Actions', 'allure-clinics'); ?></h2>
        <p><?php esc_html_e('Use these tools to populate the plugin with realistic dummy data for presentation purposes.', 'allure-clinics'); ?></p>
        
        <form method="post" action="" style="margin-bottom: 20px;">
            <?php wp_nonce_field('demo_action', 'allure_clinics_demo_nonce'); ?>
            
            <div style="margin-bottom: 15px;">
                <input type="submit" name="load_demo_data" class="button button-primary" value="<?php esc_attr_e('Load Demo Data', 'allure-clinics'); ?>">
                <p class="description">Creates 2 sample branches and 5 sample doctors directly in Bookly's Staff/Locations screens, plus a few sample leads. Safe to click again — it resets rather than duplicates.</p>
            </div>
            
            <div style="margin-bottom: 15px;">
                <input type="submit" name="create_demo_pages" class="button" value="<?php esc_attr_e('Create Demo Pages', 'allure-clinics'); ?>">
                <p class="description">Generates standard pages (Book Appointment, Patient Portal, Contact Us) with the correct shortcodes.</p>
            </div>

            <div style="margin-bottom: 15px;">
                <input type="submit" name="clear_demo_data" class="button" value="<?php esc_attr_e('Clear Demo Data', 'allure-clinics'); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete all local demo data?', 'allure-clinics'); ?>');">
                <p class="description">Deletes only the dummy data created by the "Load Demo Data" button. Real leads and bookings are untouched.</p>
            </div>
        </form>
    </div>

    <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); max-width: 800px; margin-top: 20px;">
        <h2><?php esc_html_e('3. What to Test', 'allure-clinics'); ?></h2>
        <ol style="font-size: 14px; line-height: 1.6;">
            <li>Visit the <strong>Book Appointment</strong> page.</li>
            <li>Complete a booking via the Bookly form.</li>
            <li>Open Bookly's own <a href="admin.php?page=bookly-appointments">Appointments Calendar</a> to confirm it appears.</li>
            <li>Open the <strong>Allure CRM &rarr; Leads</strong> page and submit the <em>Contact Us</em> form to confirm a lead appears.</li>
            <li>Log into the <strong>Patient Portal</strong> page with the mobile number used for booking and verify the OTP login shows that appointment.</li>
        </ol>
    </div>

    <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); max-width: 800px; margin-top: 20px;">
        <h2><?php esc_html_e('4. Where things live now', 'allure-clinics'); ?></h2>
        <p>Because Bookly is now the master system for bookings, you manage your clinic data natively within Bookly:</p>
        <ul style="font-size: 14px; line-height: 1.6;">
            <li><strong>Doctors:</strong> Manage at <a href="admin.php?page=bookly-staff">Bookly &rarr; Staff Members</a>.</li>
            <li><strong>Branches:</strong> Manage at <a href="admin.php?page=bookly-locations">Bookly &rarr; Locations</a>.</li>
            <li><strong>Appointments:</strong> Manage at <a href="admin.php?page=bookly-appointments">Bookly &rarr; Appointments</a> or <a href="admin.php?page=bookly-calendar">Calendar</a>.</li>
            <li><strong>Patients:</strong> Manage at <a href="admin.php?page=bookly-customers">Bookly &rarr; Customers</a>.</li>
        </ul>
        <p><em>Do not look for these in the Allure CRM menu anymore!</em></p>
    </div>

    <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); max-width: 800px; margin-top: 20px;">
        <h2><?php esc_html_e('For Developers (Architecture Overview)', 'allure-clinics'); ?></h2>
        <p><?php esc_html_e('This plugin acts as a Sync/Integration layer between Bookly and the external CRM.', 'allure-clinics'); ?></p>
        
        <table class="wp-list-table widefat fixed striped" style="margin-top: 15px;">
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
                            <li><code>POST /wp-json/allure/v1/auth/otp/request</code> - Request OTP for Patient Login</li>
                            <li><code>POST /wp-json/allure/v1/auth/otp/verify</code> - Verify OTP & get Session Token</li>
                            <li><code>POST /wp-json/allure/v1/leads</code> - Capture Lead (Pushes to CRM & Emails Admin)</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td><strong>Frontend Shortcodes</strong></td>
                    <td>
                        <ul>
                            <li><code>[bookly-form]</code> - Bookly's multi-step booking widget</li>
                            <li><code>[allure_patient_portal]</code> - OTP login & dashboard</li>
                            <li><code>[allure_lead_form service="Botox"]</code> - Lead capture form</li>
                            <li><strong>Wati Click-to-Chat</strong> - Auto-injected if number is set in settings</li>
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
