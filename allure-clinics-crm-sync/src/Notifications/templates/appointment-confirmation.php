<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php esc_html_e('Appointment Confirmation', 'allure-clinics'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px; }
        .header { background: #f8f9fa; padding: 15px; text-align: center; font-size: 20px; font-weight: bold; border-bottom: 2px solid #ddd; }
        .details { margin: 20px 0; }
        .details th { text-align: left; padding: 8px; background: #fdfdfd; }
        .details td { padding: 8px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <?php esc_html_e('Allure Clinics - Appointment Confirmed', 'allure-clinics'); ?>
        </div>
        
        <p><?php printf(esc_html__('Dear %s,', 'allure-clinics'), esc_html($patient['name'])); ?></p>
        <p><?php esc_html_e('Your appointment has been successfully booked. Below are the details:', 'allure-clinics'); ?></p>
        
        <table class="details" width="100%">
            <tr>
                <th><?php esc_html_e('Doctor', 'allure-clinics'); ?></th>
                <td><?php echo esc_html($doctor['name']); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Branch', 'allure-clinics'); ?></th>
                <td><?php echo esc_html($branch['name']); ?><br><small><?php echo esc_html($branch['address']); ?></small></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Date', 'allure-clinics'); ?></th>
                <td><?php echo esc_html($slot['date']); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Time', 'allure-clinics'); ?></th>
                <td><?php echo esc_html($slot['start_time']) . ' - ' . esc_html($slot['end_time']); ?></td>
            </tr>
        </table>
        
        <p>
            <a href="#" style="display:inline-block; padding:10px 15px; background:#007bff; color:#fff; text-decoration:none; border-radius:5px;">
                <?php esc_html_e('Add to Calendar', 'allure-clinics'); ?>
            </a>
        </p>

        <div class="footer">
            <?php esc_html_e('If you need to reschedule or cancel, please contact us or login to the patient portal.', 'allure-clinics'); ?>
        </div>
    </div>
</body>
</html>
