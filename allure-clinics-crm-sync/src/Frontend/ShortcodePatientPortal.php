<?php

namespace AllureClinics\Frontend;

class ShortcodePatientPortal {

    public function __construct() {
        add_shortcode('allure_patient_portal', [$this, 'render']);
    }

    public function render($atts) {
        ob_start();
        ?>
        <div class="ac-portal-container" style="max-width: 800px; margin: 0 auto; font-family: sans-serif;">
            
            <!-- Login State -->
            <div id="ac-portal-login" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 400px; margin: 0 auto;">
                <h3 style="text-align:center;"><?php esc_html_e('Patient Portal Login', 'allure-clinics'); ?></h3>
                <p style="text-align:center; color:#666;"><?php esc_html_e('Enter your mobile number to receive a secure OTP.', 'allure-clinics'); ?></p>
                
                <div id="ac-portal-step-phone">
                    <input type="tel" id="ac_portal_mobile" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px;" placeholder="+966500000000">
                    <button id="ac_portal_request_otp" style="width: 100%; padding: 12px; background: #2271b1; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                        <?php esc_html_e('Send OTP', 'allure-clinics'); ?>
                    </button>
                </div>

                <div id="ac-portal-step-otp" style="display:none;">
                    <p style="color:#007017; font-weight:bold; font-size:14px; text-align:center;" id="ac_portal_otp_sent_msg"></p>
                    <input type="text" id="ac_portal_otp" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px; text-align:center; letter-spacing: 5px; font-size: 18px;" placeholder="123456" maxlength="6">
                    <button id="ac_portal_verify_otp" style="width: 100%; padding: 12px; background: #2271b1; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                        <?php esc_html_e('Login', 'allure-clinics'); ?>
                    </button>
                </div>
                
                <div id="ac-portal-msg" style="margin-top: 15px; padding: 10px; border-radius: 4px; display:none;"></div>
            </div>

            <!-- Dashboard State -->
            <div id="ac-portal-dashboard" style="display:none;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
                    <h2><?php esc_html_e('Welcome, ', 'allure-clinics'); ?><span id="ac_portal_patient_name"></span></h2>
                    <button id="ac_portal_logout" style="padding: 8px 16px; background: #d63638; color: #fff; border: none; border-radius: 4px; cursor: pointer;"><?php esc_html_e('Logout', 'allure-clinics'); ?></button>
                </div>

                <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px;">
                    <h3><?php esc_html_e('Upcoming Appointments', 'allure-clinics'); ?></h3>
                    <div id="ac_portal_upcoming"></div>
                </div>

                <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <h3><?php esc_html_e('Past Appointments', 'allure-clinics'); ?></h3>
                    <div id="ac_portal_past"></div>
                </div>
            </div>

        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            let sessionToken = localStorage.getItem('ac_session_token') || null;
            let currentMobile = '';

            const dom = {
                login: document.getElementById('ac-portal-login'),
                dashboard: document.getElementById('ac-portal-dashboard'),
                stepPhone: document.getElementById('ac-portal-step-phone'),
                stepOtp: document.getElementById('ac-portal-step-otp'),
                mobile: document.getElementById('ac_portal_mobile'),
                otp: document.getElementById('ac_portal_otp'),
                btnRequest: document.getElementById('ac_portal_request_otp'),
                btnVerify: document.getElementById('ac_portal_verify_otp'),
                btnLogout: document.getElementById('ac_portal_logout'),
                msg: document.getElementById('ac-portal-msg'),
                patientName: document.getElementById('ac_portal_patient_name'),
                upcoming: document.getElementById('ac_portal_upcoming'),
                past: document.getElementById('ac_portal_past'),
                otpSentMsg: document.getElementById('ac_portal_otp_sent_msg')
            };

            function showMsg(text, isError) {
                dom.msg.style.display = 'block';
                dom.msg.style.backgroundColor = isError ? '#f8d7da' : '#d4edda';
                dom.msg.style.color = isError ? '#721c24' : '#155724';
                dom.msg.innerText = text;
            }

            function fetchDashboard() {
                dom.login.style.display = 'none';
                dom.dashboard.style.display = 'block';

                // Fetch Profile
                fetch('/wp-json/allure/v1/patient/profile', {
                    headers: { 'Authorization': 'Bearer ' + sessionToken }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.code && data.code === 'rest_forbidden') {
                        logout(); return;
                    }
                    dom.patientName.innerText = data.name;
                });

                // Fetch Appointments
                fetch('/wp-json/allure/v1/patient/appointments', {
                    headers: { 'Authorization': 'Bearer ' + sessionToken }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.code && data.code === 'rest_forbidden') return;
                    
                    let upcomingHtml = '';
                    let pastHtml = '';
                    
                    if (data.appointments && data.appointments.length > 0) {
                        const now = new Date();
                        data.appointments.forEach(app => {
                            const appDate = new Date(app.date + 'T' + app.start_time);
                            const html = `
                                <div style="padding: 15px; border: 1px solid #eee; border-radius: 4px; margin-bottom: 10px; display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <strong style="font-size:16px;">${app.date} at ${app.start_time}</strong><br>
                                        <span style="color:#666;">Dr. ${app.doctor_name} (${app.branch_name})</span>
                                    </div>
                                    <div>
                                        <span style="background:#e5f5fa; color:#007cba; padding:4px 8px; border-radius:4px; font-size:12px;">${app.status.toUpperCase()}</span>
                                    </div>
                                </div>
                            `;
                            if (appDate > now) {
                                upcomingHtml += html;
                            } else {
                                pastHtml += html;
                            }
                        });
                    }

                    dom.upcoming.innerHTML = upcomingHtml || '<p style="color:#777;">No upcoming appointments.</p>';
                    dom.past.innerHTML = pastHtml || '<p style="color:#777;">No past appointments.</p>';
                });
            }

            function logout() {
                localStorage.removeItem('ac_session_token');
                sessionToken = null;
                dom.login.style.display = 'block';
                dom.dashboard.style.display = 'none';
                dom.stepPhone.style.display = 'block';
                dom.stepOtp.style.display = 'none';
                dom.mobile.value = '';
                dom.otp.value = '';
                dom.msg.style.display = 'none';
            }

            if (sessionToken) {
                fetchDashboard();
            }

            dom.btnRequest.addEventListener('click', function() {
                currentMobile = dom.mobile.value.trim();
                if (!currentMobile) return showMsg('Please enter mobile number.', true);
                
                dom.btnRequest.disabled = true;
                dom.btnRequest.innerText = '...';

                fetch('/wp-json/allure/v1/auth/otp/request', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mobile: currentMobile })
                })
                .then(res => res.json().then(data => ({status: res.status, body: data})))
                .then(res => {
                    if (res.status === 200) {
                        dom.msg.style.display = 'none';
                        dom.stepPhone.style.display = 'none';
                        dom.stepOtp.style.display = 'block';
                        dom.otpSentMsg.innerText = res.body.message; // "OTP sent via SMS (check logs for demo)"
                    } else {
                        showMsg(res.body.error, true);
                    }
                })
                .finally(() => {
                    dom.btnRequest.disabled = false;
                    dom.btnRequest.innerText = '<?php esc_html_e('Send OTP', 'allure-clinics'); ?>';
                });
            });

            dom.btnVerify.addEventListener('click', function() {
                const otp = dom.otp.value.trim();
                if (!otp) return showMsg('Please enter OTP.', true);

                dom.btnVerify.disabled = true;
                dom.btnVerify.innerText = '...';

                fetch('/wp-json/allure/v1/auth/otp/verify', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mobile: currentMobile, otp: otp })
                })
                .then(res => res.json().then(data => ({status: res.status, body: data})))
                .then(res => {
                    if (res.status === 200) {
                        sessionToken = res.body.session_token;
                        localStorage.setItem('ac_session_token', sessionToken);
                        fetchDashboard();
                    } else {
                        showMsg(res.body.error, true);
                    }
                })
                .finally(() => {
                    dom.btnVerify.disabled = false;
                    dom.btnVerify.innerText = '<?php esc_html_e('Login', 'allure-clinics'); ?>';
                });
            });

            dom.btnLogout.addEventListener('click', logout);
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
