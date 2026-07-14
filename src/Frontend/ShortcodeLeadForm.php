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
        <div class="ac-premium-form" style="max-width: 500px; margin: 40px auto; background: #ffffff; padding: 40px; border-radius: 20px; box-shadow: 0 12px 40px rgba(0,0,0,0.08); font-family: 'Outfit', 'Inter', sans-serif;">
            
            <div style="text-align: center; margin-bottom: 30px;">
                <img src="https://shoaib.standardtouch.com/allureclinics/wp-content/uploads/2026/07/allure-logo-1.png" alt="Allure Clinics Logo" style="max-height: 60px; margin-bottom: 15px; display: inline-block;">
                <h3 style="margin: 0; color: #2c3e50; font-weight: 600; font-size: 24px; letter-spacing: -0.5px;"><?php esc_html_e('Request a Callback', 'allure-clinics'); ?></h3>
                <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 15px;"><?php esc_html_e('Leave your details and we will contact you.', 'allure-clinics'); ?></p>
            </div>

            <form id="ac-lead-form">
                <input type="hidden" id="ac_lead_service" value="<?php echo esc_attr($atts['service']); ?>">
                <input type="hidden" id="ac_lead_campaign" value="<?php echo esc_attr($atts['campaign']); ?>">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 500; color: #34495e; margin-bottom: 8px;"><?php esc_html_e('Full Name', 'allure-clinics'); ?></label>
                    <input type="text" id="ac_lead_name" required class="ac-input-premium">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 500; color: #34495e; margin-bottom: 8px;"><?php esc_html_e('Mobile Number', 'allure-clinics'); ?></label>
                    <input type="tel" id="ac_lead_mobile" required class="ac-input-premium" placeholder="+966500000000">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 500; color: #34495e; margin-bottom: 8px;"><?php esc_html_e('Email (Optional)', 'allure-clinics'); ?></label>
                    <input type="email" id="ac_lead_email" class="ac-input-premium">
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: 500; color: #34495e; margin-bottom: 8px;"><?php esc_html_e('How can we help you?', 'allure-clinics'); ?></label>
                    <textarea id="ac_lead_message" rows="3" class="ac-input-premium" style="resize: vertical;"></textarea>
                </div>
                
                <div id="ac-lead-msg" style="display:none; margin-bottom: 20px; padding: 15px; border-radius: 8px; font-weight: 500; text-align: center;"></div>

                <button type="submit" id="ac_lead_submit" class="ac-btn-premium">
                    <?php esc_html_e('Submit Request', 'allure-clinics'); ?>
                </button>
            </form>
        </div>

        <!-- Preconnect to Google Fonts for premium typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            .ac-input-premium { width: 100%; padding: 14px 16px; border: 1px solid #dcdde1; border-radius: 10px; font-size: 16px; font-family: inherit; color: #2c3e50; transition: all 0.3s; background: #fff; box-sizing: border-box; }
            .ac-input-premium:focus { border-color: #5ab0a9; outline: none; box-shadow: 0 0 0 4px rgba(90,176,169,0.15); }
            
            .ac-btn-premium { width: 100%; padding: 15px; background: linear-gradient(135deg, #5ab0a9 0%, #469a93 100%); color: #fff; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-family: inherit; box-shadow: 0 4px 15px rgba(90,176,169,0.3); }
            .ac-btn-premium:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(90,176,169,0.4); }
            .ac-btn-premium:disabled { background: #bdc3c7; box-shadow: none; cursor: not-allowed; transform: none; }
        </style>

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

                fetch('<?php echo esc_url(rest_url('allure/v1/leads')); ?>', {
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
