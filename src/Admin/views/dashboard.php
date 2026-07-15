<div class="wrap">
    <h1><?php esc_html_e('Allure CRM Dashboard', 'allure-clinics'); ?></h1>
    <hr>
    
    <div style="display:flex; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
        
        <!-- System Status Panel -->
        <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); flex: 1 1 300px;">
            <h3><?php esc_html_e('System Status', 'allure-clinics'); ?></h3>
            <ul style="font-size: 14px; line-height: 1.8;">
                <li><strong>Bookly Integration:</strong> <?php echo $bookly_active ? '✅ Active' : '❌ Not Detected'; ?></li>
                <li><strong>CRM Adapter:</strong> <?php echo esc_html($crm_status); ?></li>
                <li><strong>Last CRM Sync:</strong> <?php echo esc_html($last_sync_time); ?> (Result: <?php echo esc_html($last_sync_result); ?>)</li>
                <li><strong>Last Reminder Run:</strong> <?php echo esc_html($last_reminder_time); ?></li>
                <li><strong>Demo Data:</strong> <?php echo $demo_data_loaded ? 'Loaded' : 'Not Loaded'; ?></li>
            </ul>
        </div>

        <!-- Today's Appointments -->
        <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); min-width: 200px;">
            <h3><?php esc_html_e('Today\'s Appointments', 'allure-clinics'); ?></h3>
            <p style="font-size: 24px; font-weight: bold; margin: 0; color: #2271b1;">
                <?php 
                if ($bookly_active) {
                    echo esc_html($appointments_today); 
                } else {
                    echo '<span style="font-size:14px; color:#d63638;">Bookly not active</span>';
                }
                ?>
            </p>
        </div>

        <!-- Leads -->
        <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); min-width: 200px;">
            <h3><?php esc_html_e('Leads', 'allure-clinics'); ?></h3>
            <p style="font-size: 24px; font-weight: bold; margin: 0; color: #2271b1;">
                <?php echo esc_html($total_leads); ?> Total
            </p>
            <p style="font-size: 14px; margin-top: 5px;">
                (<?php echo esc_html($new_leads); ?> New)
            </p>
        </div>

        <!-- Sync Logs -->
        <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); min-width: 200px;">
            <h3><?php esc_html_e('Pending Sync', 'allure-clinics'); ?></h3>
            <p style="font-size: 24px; font-weight: bold; margin: 0; color: #f56e28;">
                <?php echo esc_html($pending_sync); ?>
            </p>
        </div>

        <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); min-width: 200px;">
            <h3><?php esc_html_e('Failed Syncs', 'allure-clinics'); ?></h3>
            <p style="font-size: 24px; font-weight: bold; margin: 0; color: #d63638;">
                <?php echo esc_html($failed_sync); ?>
            </p>
        </div>

    </div>
</div>
