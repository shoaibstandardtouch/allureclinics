<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2><?php esc_html_e('New Lead Captured', 'allure-clinics'); ?></h2>
    <p><?php esc_html_e('A new lead has been submitted via the website:', 'allure-clinics'); ?></p>
    
    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px;">
        <tr>
            <th style="text-align: left; background-color: #f9f9f9;"><?php esc_html_e('Name', 'allure-clinics'); ?></th>
            <td><?php echo esc_html($leadData['name']); ?></td>
        </tr>
        <tr>
            <th style="text-align: left; background-color: #f9f9f9;"><?php esc_html_e('Mobile', 'allure-clinics'); ?></th>
            <td><?php echo esc_html($leadData['mobile']); ?></td>
        </tr>
        <tr>
            <th style="text-align: left; background-color: #f9f9f9;"><?php esc_html_e('Email', 'allure-clinics'); ?></th>
            <td><?php echo esc_html($leadData['email']); ?></td>
        </tr>
        <tr>
            <th style="text-align: left; background-color: #f9f9f9;"><?php esc_html_e('Service Interest', 'allure-clinics'); ?></th>
            <td><?php echo esc_html($leadData['service_interest']); ?></td>
        </tr>
        <tr>
            <th style="text-align: left; background-color: #f9f9f9;"><?php esc_html_e('Campaign Source', 'allure-clinics'); ?></th>
            <td><?php echo esc_html($leadData['campaign_source']); ?></td>
        </tr>
        <tr>
            <th style="text-align: left; background-color: #f9f9f9;"><?php esc_html_e('Message', 'allure-clinics'); ?></th>
            <td><?php echo nl2br(esc_html($leadData['message'])); ?></td>
        </tr>
    </table>
    
    <p style="margin-top: 20px;">
        <a href="<?php echo admin_url('admin.php?page=allure-crm-leads'); ?>"><?php esc_html_e('View in Dashboard', 'allure-clinics'); ?></a>
    </p>
</body>
</html>
