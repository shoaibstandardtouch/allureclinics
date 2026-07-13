<div class="wrap">
    <h1><?php esc_html_e('Allure CRM Settings', 'allure-clinics'); ?></h1>
    <hr>
    
    <form method="post" action="">
        <?php wp_nonce_field('save_settings', 'allure_clinics_settings_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="ac_crm_adapter"><?php esc_html_e('CRM Adapter', 'allure-clinics'); ?></label></th>
                <td>
                    <select name="ac_crm_adapter" id="ac_crm_adapter">
                        <option value="null" <?php selected($current_adapter, 'null'); ?>><?php esc_html_e('Not configured (Null Adapter)', 'allure-clinics'); ?></option>
                        <!-- Future adapters will be added here -->
                    </select>
                    <p class="description"><?php esc_html_e('Select the CRM integration to use. Leave as Null Adapter for local testing.', 'allure-clinics'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row"><label for="ac_wati_phone"><?php esc_html_e('Wati WhatsApp Number', 'allure-clinics'); ?></label></th>
                <td>
                    <input type="text" name="ac_wati_phone" id="ac_wati_phone" value="<?php echo esc_attr($wati_phone); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Enter the phone number with country code (e.g. 966500000000) for the click-to-chat widget.', 'allure-clinics'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="ac_wati_message"><?php esc_html_e('Wati Default Message', 'allure-clinics'); ?></label></th>
                <td>
                    <textarea name="ac_wati_message" id="ac_wati_message" rows="3" class="large-text"><?php echo esc_textarea($wati_message); ?></textarea>
                    <p class="description"><?php esc_html_e('Default pre-filled message for WhatsApp.', 'allure-clinics'); ?></p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>
