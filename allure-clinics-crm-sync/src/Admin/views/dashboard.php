<div class="wrap">
    <h1><?php esc_html_e('Allure CRM Dashboard', 'allure-clinics'); ?></h1>
    <hr>
    
    <div style="display:flex; gap: 20px; margin-top: 20px;">
        <div style="background:#fff; padding:20px; border:1px solid #ccd0d4; box-shadow:0 1px 1px rgba(0,0,0,.04); min-width: 200px;">
            <h3><?php esc_html_e('Today\'s Appointments', 'allure-clinics'); ?></h3>
            <p style="font-size: 24px; font-weight: bold; margin: 0; color: #2271b1;">
                <?php echo esc_html($appointments_today); ?>
            </p>
        </div>

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
