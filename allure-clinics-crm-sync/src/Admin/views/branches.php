<div class="wrap">
    <h1><?php esc_html_e('Allure CRM - Branches', 'allure-clinics'); ?></h1>
    <hr>
    
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        <div style="flex: 2; min-width: 400px;">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Name', 'allure-clinics'); ?></th>
                        <th><?php esc_html_e('Address', 'allure-clinics'); ?></th>
                        <th><?php esc_html_e('Phone', 'allure-clinics'); ?></th>
                        <th><?php esc_html_e('Sync Status', 'allure-clinics'); ?></th>
                        <th><?php esc_html_e('Actions', 'allure-clinics'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($branches)) : ?>
                        <?php foreach ($branches as $branch) : ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html($branch['name']); ?></strong><br>
                                    <span style="color:#777;"><?php echo esc_html($branch['name_ar']); ?></span>
                                </td>
                                <td>
                                    <?php echo esc_html($branch['address']); ?><br>
                                    <span style="color:#777;"><?php echo esc_html($branch['address_ar']); ?></span>
                                </td>
                                <td><?php echo esc_html($branch['phone']); ?></td>
                                <td>
                                    <?php if (is_null($branch['crm_id'])) : ?>
                                        <span style="color: #f56e28; font-size: 12px;"><?php esc_html_e('Local only - not synced to CRM', 'allure-clinics'); ?></span>
                                    <?php else : ?>
                                        <span style="color: #007017; font-size: 12px;"><?php echo esc_html($branch['sync_status']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="post" action="" style="display:inline;">
                                        <?php wp_nonce_field('save_branch', 'allure_clinics_branch_nonce'); ?>
                                        <input type="hidden" name="branch_id" value="<?php echo esc_attr($branch['id']); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="submit" class="button button-small" value="<?php esc_attr_e('Delete', 'allure-clinics'); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure?', 'allure-clinics'); ?>');">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5"><?php esc_html_e('No branches found.', 'allure-clinics'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="flex: 1; min-width: 300px; background:#fff; padding:20px; border:1px solid #ccd0d4;">
            <h3><?php esc_html_e('Add New Branch', 'allure-clinics'); ?></h3>
            <form method="post" action="">
                <?php wp_nonce_field('save_branch', 'allure_clinics_branch_nonce'); ?>
                
                <p>
                    <label><?php esc_html_e('Name (English)', 'allure-clinics'); ?></label><br>
                    <input type="text" name="name" required class="regular-text" style="width:100%;">
                </p>
                <p>
                    <label><?php esc_html_e('Name (Arabic)', 'allure-clinics'); ?></label><br>
                    <input type="text" name="name_ar" class="regular-text" style="width:100%;">
                </p>
                <p>
                    <label><?php esc_html_e('Address (English)', 'allure-clinics'); ?></label><br>
                    <textarea name="address" rows="2" style="width:100%;"></textarea>
                </p>
                <p>
                    <label><?php esc_html_e('Address (Arabic)', 'allure-clinics'); ?></label><br>
                    <textarea name="address_ar" rows="2" style="width:100%;"></textarea>
                </p>
                <p>
                    <label><?php esc_html_e('Phone', 'allure-clinics'); ?></label><br>
                    <input type="text" name="phone" class="regular-text" style="width:100%;">
                </p>
                
                <?php submit_button(__('Save Branch', 'allure-clinics')); ?>
            </form>
        </div>
        
    </div>
</div>
