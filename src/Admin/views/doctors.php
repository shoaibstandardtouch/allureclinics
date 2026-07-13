<div class="wrap">
    <h1><?php esc_html_e('Allure CRM - Doctors', 'allure-clinics'); ?></h1>
    <hr>
    
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        
        <div style="flex: 2; min-width: 500px;">
            <p><em><?php esc_html_e('Note: Demo data — replace with real staff details before go-live.', 'allure-clinics'); ?></em></p>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 60px;"><?php esc_html_e('Photo', 'allure-clinics'); ?></th>
                        <th><?php esc_html_e('Name', 'allure-clinics'); ?></th>
                        <th><?php esc_html_e('Branch', 'allure-clinics'); ?></th>
                        <th><?php esc_html_e('Specialties', 'allure-clinics'); ?></th>
                        <th><?php esc_html_e('Sync Status', 'allure-clinics'); ?></th>
                        <th><?php esc_html_e('Actions', 'allure-clinics'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($doctors)) : ?>
                        <?php foreach ($doctors as $doctor) : ?>
                            <?php $specialties = implode(', ', json_decode($doctor['specialties'], true) ?? []); ?>
                            <tr>
                                <td>
                                    <div style="width:50px; height:50px; background:#eee; border-radius:50%;"></div>
                                </td>
                                <td>
                                    <strong><?php echo esc_html($doctor['name']); ?></strong><br>
                                    <span style="color:#777;"><?php echo esc_html($doctor['name_ar']); ?></span>
                                </td>
                                <td><?php echo esc_html($doctor['branch_name']); ?></td>
                                <td><?php echo esc_html($specialties); ?></td>
                                <td>
                                    <?php if (is_null($doctor['crm_id'])) : ?>
                                        <span style="color: #f56e28; font-size: 12px;"><?php esc_html_e('Local only', 'allure-clinics'); ?></span>
                                    <?php else : ?>
                                        <span style="color: #007017; font-size: 12px;"><?php echo esc_html($doctor['sync_status']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="post" action="" style="display:inline;">
                                        <?php wp_nonce_field('save_doctor', 'allure_clinics_doctor_nonce'); ?>
                                        <input type="hidden" name="doctor_id" value="<?php echo esc_attr($doctor['id']); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="submit" class="button button-small" value="<?php esc_attr_e('Delete', 'allure-clinics'); ?>" onclick="return confirm('<?php esc_attr_e('Are you sure?', 'allure-clinics'); ?>');">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6"><?php esc_html_e('No doctors found.', 'allure-clinics'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="flex: 1; min-width: 300px; background:#fff; padding:20px; border:1px solid #ccd0d4;">
            <h3><?php esc_html_e('Add New Doctor', 'allure-clinics'); ?></h3>
            <form method="post" action="">
                <?php wp_nonce_field('save_doctor', 'allure_clinics_doctor_nonce'); ?>
                
                <p>
                    <label><?php esc_html_e('Branch', 'allure-clinics'); ?></label><br>
                    <select name="branch_id" required style="width:100%;">
                        <option value=""><?php esc_html_e('-- Select Branch --', 'allure-clinics'); ?></option>
                        <?php foreach ($branches as $branch) : ?>
                            <option value="<?php echo esc_attr($branch['id']); ?>"><?php echo esc_html($branch['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                
                <p>
                    <label><?php esc_html_e('Name (English)', 'allure-clinics'); ?></label><br>
                    <input type="text" name="name" required class="regular-text" style="width:100%;">
                </p>
                <p>
                    <label><?php esc_html_e('Name (Arabic)', 'allure-clinics'); ?></label><br>
                    <input type="text" name="name_ar" class="regular-text" style="width:100%;">
                </p>
                <p>
                    <label><?php esc_html_e('Bio (English)', 'allure-clinics'); ?></label><br>
                    <textarea name="bio" rows="3" style="width:100%;"></textarea>
                </p>
                <p>
                    <label><?php esc_html_e('Bio (Arabic)', 'allure-clinics'); ?></label><br>
                    <textarea name="bio_ar" rows="3" style="width:100%;"></textarea>
                </p>
                <p>
                    <label><?php esc_html_e('Specialties (Comma separated)', 'allure-clinics'); ?></label><br>
                    <input type="text" name="specialties" class="regular-text" style="width:100%;" placeholder="Botox, Dermal Fillers">
                </p>
                <p>
                    <label><?php esc_html_e('Qualifications (Comma separated)', 'allure-clinics'); ?></label><br>
                    <input type="text" name="qualifications" class="regular-text" style="width:100%;">
                </p>
                <p>
                    <label><?php esc_html_e('Years Experience', 'allure-clinics'); ?></label><br>
                    <input type="number" name="years_experience" class="regular-text" style="width:100%;" min="0">
                </p>
                
                <?php submit_button(__('Save Doctor', 'allure-clinics')); ?>
            </form>
        </div>
        
    </div>
</div>
