<?php

namespace AllureClinics\Frontend;

class ShortcodeLeadForm {

    public function __construct() {
        add_shortcode('allure_lead_form', [$this, 'render']);
    }

    public function render($atts) {
        $atts = shortcode_atts([
            'service' => '',
            'campaign' => 'Website'
        ], $atts, 'allure_lead_form');

        ob_start();
        ?>
        <div class="ac-lead-form-container" style="max-width: 500px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <form id="ac-lead-form">
                <input type="hidden" id="ac_lead_service" value="<?php echo esc_attr($atts['service']); ?>">
                <input type="hidden" id="ac_lead_campaign" value="<?php echo esc_attr($atts['campaign']); ?>">
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e('Full Name', 'allure-clinics'); ?></label>
                    <input type="text" id="ac_lead_name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e('Mobile Number', 'allure-clinics'); ?></label>
                    <input type="tel" id="ac_lead_mobile" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" placeholder="+966500000000">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e('Email (Optional)', 'allure-clinics'); ?></label>
                    <input type="email" id="ac_lead_email" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;"><?php esc_html_e('How can we help you?', 'allure-clinics'); ?></label>
                    <textarea id="ac_lead_message" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
                </div>
                
                <div id="ac-lead-msg" style="display:none; margin-bottom: 15px; padding: 10px; border-radius: 4px;"></div>

                <button type="submit" id="ac_lead_submit" style="width: 100%; padding: 12px; background: #2271b1; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                    <?php esc_html_e('Submit Request', 'allure-clinics'); ?>
                </button>
            </form>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ac-lead-form');
            if(!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = document.getElementById('ac_lead_submit');
                const msgBox = document.getElementById('ac-lead-msg');
                btn.disabled = true;
                btn.innerText = '<?php esc_html_e('Sending...', 'allure-clinics'); ?>';
                
                const data = {
                    name: document.getElementById('ac_lead_name').value,
                    mobile: document.getElementById('ac_lead_mobile').value,
                    email: document.getElementById('ac_lead_email').value,
                    service_interest: document.getElementById('ac_lead_service').value,
                    campaign_source: document.getElementById('ac_lead_campaign').value,
                    message: document.getElementById('ac_lead_message').value
                };

                fetch('/wp-json/allure/v1/leads', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(res => res.json().then(data => ({status: res.status, body: data})))
                .then(res => {
                    msgBox.style.display = 'block';
                    if (res.status === 201) {
                        msgBox.style.backgroundColor = '#d4edda';
                        msgBox.style.color = '#155724';
                        msgBox.innerText = res.body.message;
                        form.reset();
                    } else {
                        msgBox.style.backgroundColor = '#f8d7da';
                        msgBox.style.color = '#721c24';
                        msgBox.innerText = res.body.error || 'Error submitting request.';
                    }
                })
                .catch(err => {
                    msgBox.style.display = 'block';
                    msgBox.style.backgroundColor = '#f8d7da';
                    msgBox.style.color = '#721c24';
                    msgBox.innerText = 'Network error.';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerText = '<?php esc_html_e('Submit Request', 'allure-clinics'); ?>';
                });
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
