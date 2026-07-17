<div class="wrap">
    <h1><?php esc_html_e('Allure CRM Integration - Getting Started', 'allure-clinics'); ?></h1>
    <hr>
    
    <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); max-width: 800px; margin-top: 20px;">
        <h2><?php esc_html_e('Go-Live Checklist', 'allure-clinics'); ?></h2>
        <p><?php esc_html_e('Follow these steps to transition from demo mode to a real, live environment:', 'allure-clinics'); ?></p>
        <ul style="font-size: 15px; line-height: 1.8;">
            <li>
                ☐ <strong>Clear all demo data:</strong> Use the "Clear Demo Data" button below to remove demo leads. Ensure no demo doctors or branches remain in Bookly (those were entered manually, so check Bookly's own screens).
            </li>
            <li>
                ☐ <strong>Clean Up Legacy Duplicate Leads:</strong> Run the cleanup tool in the Demo Actions section to clear any old orphaned leads.
            </li>
            <li>
                ☐ <strong>Enter Taqnyat Credentials:</strong> Add real Taqnyat bearer token and sender name in Settings. (Until then, the site safely runs in Test Mode/log-only).
            </li>
            <li>
                ☐ <strong>Select Real CRM Adapter:</strong> Ensure a real CRM adapter is implemented and selected in Settings once the client's CRM is confirmed (currently `NullAdapter` is active, which is safe).
            </li>
            <li>
                ☐ <strong>Enter Real Clinic Data:</strong> Enter actual doctors, branches, and services into Bookly, including real photos and descriptions.
            </li>
        </ul>
    </div>

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

            <div style="margin-bottom: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                <?php if ($has_duplicates): ?>
                    <input type="submit" name="cleanup_duplicate_leads" class="button" value="<?php esc_attr_e('Clean Up Legacy Duplicate Leads', 'allure-clinics'); ?>" onclick="return confirm('<?php esc_attr_e('This will safely delete exact duplicate legacy leads, keeping only the oldest record for each. Proceed?', 'allure-clinics'); ?>');">
                    <p class="description" style="color: #d63638;"><strong>Warning:</strong> Found potential legacy duplicate leads from before the deduplication fix. Click this button to safely clean them up by content matching.</p>
                <?php else: ?>
                    <input type="submit" name="cleanup_duplicate_leads" class="button" value="<?php esc_attr_e('Clean Up Legacy Duplicate Leads', 'allure-clinics'); ?>" disabled>
                    <p class="description" style="color: #008a20;">No duplicate legacy leads found. The database is clean!</p>
                <?php endif; ?>
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
        <h2><?php esc_html_e('Customer Journey', 'allure-clinics'); ?></h2>
        <ol style="font-size: 14px; line-height: 1.6;">
            <li><strong>Discover:</strong> Browse doctors/services on the site.</li>
            <li><strong>Book:</strong> Visit the Book Appointment page and fill out Bookly's native booking form.</li>
            <li><strong>Confirm:</strong> Receive confirmation of the appointment.</li>
            <li><strong>Manage:</strong> Visit the Patient Portal page for OTP login. Here, view upcoming appointments or profile information.</li>
            <li><strong>Inquire:</strong> Alternatively, submit an inquiry via Contact Us or campaign lead forms if not ready to book. This creates a Lead for staff follow-up.</li>
        </ol>
    </div>

    <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); max-width: 800px; margin-top: 20px;">
        <h2><?php esc_html_e('Doctor / Staff Management Journey', 'allure-clinics'); ?></h2>
        <ol style="font-size: 14px; line-height: 1.6;">
            <li><strong>Add Doctors:</strong> Manage in <a href="admin.php?page=bookly-staff">Bookly &rarr; Staff</a>.</li>
            <li><strong>Add Branches:</strong> Manage in <a href="admin.php?page=bookly-locations">Bookly &rarr; Locations</a>.</li>
            <li><strong>Add Services:</strong> Manage in <a href="admin.php?page=bookly-services">Bookly &rarr; Services</a>.</li>
            <li><strong>Manage Bookings:</strong> View and manage appointments in <a href="admin.php?page=bookly-appointments">Bookly &rarr; Appointments</a> or the <a href="admin.php?page=bookly-calendar">Calendar</a>.</li>
            <li><strong>Emergency Slots:</strong> To block out time for an emergency walk-in without deleting regular availability: Go to <strong>Bookly &rarr; Settings &rarr; Custom Statuses</strong> and create a status named "Emergency Hold" (ensure "Occupies time" / Busy is checked). Staff can then assign this status to a manual placeholder appointment in the Calendar to block public booking.</li>
            <li><strong>Waitlist:</strong> Handle waitlisted patients via Bookly's waiting list status.</li>
            <li><strong>Leads:</strong> Follow up on inquiries in <strong>Allure CRM &rarr; Leads</strong>.</li>
            <li><strong>Sync Health:</strong> Monitor CRM sync health in <strong>Allure CRM &rarr; Dashboard</strong>.</li>
            <li><strong>Configuration:</strong> Configure SMS, WhatsApp, and CRM adapters in <strong>Allure CRM &rarr; Settings</strong>.</li>
        </ol>
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
                            <li><code>GET /wp-json/allure/v1/admin/test-otp?mobile={number}</code> - Fetch OTP in Test Mode (Admin only)</li>
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
