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
                        <?php 
                        $adapters = \AllureClinics\CRM\AdapterFactory::getAvailableAdapters();
                        foreach ($adapters as $slug => $class) : 
                            $label = $slug === 'null' ? __('Not configured (Null Adapter)', 'allure-clinics') : $class;
                        ?>
                            <option value="<?php echo esc_attr($slug); ?>" <?php selected($current_adapter, $slug); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
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

            <!-- SMS Provider Settings -->
            <tr>
                <td colspan="2"><hr><h2><?php esc_html_e('SMS & OTP Settings', 'allure-clinics'); ?></h2></td>
            </tr>

            <?php if ($current_sms_provider === 'taqnyat' && (empty($taqnyat_token) || empty($taqnyat_sender))) : ?>
                <tr>
                    <td colspan="2">
                        <div class="notice notice-warning inline"><p><?php esc_html_e('Taqnyat selected but credentials are missing — currently falling back to log-only mode.', 'allure-clinics'); ?></p></div>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <th scope="row"><label for="ac_sms_provider"><?php esc_html_e('SMS Provider', 'allure-clinics'); ?></label></th>
                <td>
                    <select name="ac_sms_provider" id="ac_sms_provider">
                        <?php 
                        $sms_providers = \AllureClinics\Auth\SmsProviderFactory::getAvailableProviders();
                        foreach ($sms_providers as $slug => $class) : 
                            $label = $slug === 'log_only' ? __('Log Only (Local Testing)', 'allure-clinics') : ($slug === 'taqnyat' ? 'Taqnyat' : $class);
                        ?>
                            <option value="<?php echo esc_attr($slug); ?>" <?php selected($current_sms_provider, $slug); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="ac_taqnyat_bearer_token"><?php esc_html_e('Taqnyat Bearer Token', 'allure-clinics'); ?></label></th>
                <td>
                    <?php 
                        $display_token = '';
                        if (!empty($taqnyat_token)) {
                            // Mask the token except for the last 4 characters
                            $len = strlen($taqnyat_token);
                            if ($len > 4) {
                                $display_token = str_repeat('&bull;', $len - 4) . substr($taqnyat_token, -4);
                            } else {
                                $display_token = str_repeat('&bull;', $len);
                            }
                        }
                    ?>
                    <input type="text" name="ac_taqnyat_bearer_token" id="ac_taqnyat_bearer_token" value="<?php echo esc_attr($taqnyat_token ? $display_token : ''); ?>" class="regular-text" placeholder="<?php echo $taqnyat_token ? esc_attr__('Leave blank to keep existing token', 'allure-clinics') : ''; ?>" onfocus="if(this.value.includes('•')) { this.value=''; }">
                    <p class="description"><?php esc_html_e('Your Taqnyat API Bearer token. (If updating, clear the masked text and paste the new token).', 'allure-clinics'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="ac_taqnyat_sender_name"><?php esc_html_e('Taqnyat Sender Name', 'allure-clinics'); ?></label></th>
                <td>
                    <input type="text" name="ac_taqnyat_sender_name" id="ac_taqnyat_sender_name" value="<?php echo esc_attr($taqnyat_sender); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Must be pre-approved by Taqnyat support.', 'allure-clinics'); ?></p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>
