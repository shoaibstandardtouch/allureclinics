<div class="wrap">
    <h1><?php esc_html_e('Allure CRM - Leads', 'allure-clinics'); ?></h1>
    <hr>
    
    <table class="wp-list-table widefat fixed striped" style="margin-top: 20px;">
        <thead>
            <tr>
                <th><?php esc_html_e('Date', 'allure-clinics'); ?></th>
                <th><?php esc_html_e('Name', 'allure-clinics'); ?></th>
                <th><?php esc_html_e('Mobile', 'allure-clinics'); ?></th>
                <th><?php esc_html_e('Service / Campaign', 'allure-clinics'); ?></th>
                <th><?php esc_html_e('Message', 'allure-clinics'); ?></th>
                <th><?php esc_html_e('Status', 'allure-clinics'); ?></th>
                <th><?php esc_html_e('Action', 'allure-clinics'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($leads)) : ?>
                <?php foreach ($leads as $lead) : ?>
                    <tr>
                        <td><?php echo esc_html(wp_date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($lead['created_at']))); ?></td>
                        <td>
                            <strong><?php echo esc_html($lead['name']); ?></strong><br>
                            <small><a href="mailto:<?php echo esc_attr($lead['email']); ?>"><?php echo esc_html($lead['email']); ?></a></small>
                        </td>
                        <td><a href="tel:<?php echo esc_attr($lead['mobile']); ?>"><?php echo esc_html($lead['mobile']); ?></a></td>
                        <td>
                            <?php echo esc_html($lead['service_interest'] ?: '-'); ?><br>
                            <small style="color: #777;"><?php echo esc_html($lead['campaign_source']); ?></small>
                        </td>
                        <td><?php echo esc_html($lead['message']); ?></td>
                        <td>
                            <?php 
                                $status_color = $lead['status'] === 'new' ? 'color: #d63638; font-weight: bold;' : 'color: #007017;';
                            ?>
                            <span style="<?php echo $status_color; ?>"><?php echo esc_html(ucfirst($lead['status'])); ?></span>
                        </td>
                        <td>
                            <?php if ($lead['status'] === 'new') : ?>
                                <form method="post" action="">
                                    <?php wp_nonce_field('update_lead', 'allure_clinics_leads_nonce'); ?>
                                    <input type="hidden" name="lead_id" value="<?php echo esc_attr($lead['id']); ?>">
                                    <input type="hidden" name="new_status" value="contacted">
                                    <input type="submit" name="update_lead_status" class="button button-small" value="<?php esc_attr_e('Mark Contacted', 'allure-clinics'); ?>">
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7"><?php esc_html_e('No leads found.', 'allure-clinics'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
