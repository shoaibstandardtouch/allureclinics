<?php

namespace AllureClinics\Frontend;

class ShortcodePatientPortal {

    public function __construct() {
        add_shortcode('allure_patient_portal', [$this, 'render']);
    }

    public function render($atts) {
        ob_start();
        ?>
        <div class="ac-premium-portal" style="max-width: 800px; margin: 40px auto; font-family: 'Outfit', 'Inter', sans-serif;">
            
            <!-- Login State -->
            <div id="ac-portal-login" style="background: #ffffff; padding: 40px; border-radius: 20px; box-shadow: 0 12px 40px rgba(0,0,0,0.08); max-width: 450px; margin: 0 auto; text-align: center;">
                <img src="https://shoaib.standardtouch.com/allureclinics/wp-content/uploads/2026/07/allure-logo-1.png" alt="Allure Clinics Logo" style="max-height: 70px; margin-bottom: 15px; display: inline-block;">
                <h3 style="margin: 0; color: #2c3e50; font-weight: 600; font-size: 26px; letter-spacing: -0.5px;"><?php esc_html_e('Patient Portal', 'allure-clinics'); ?></h3>
                <p style="margin: 5px 0 25px 0; color: #7f8c8d; font-size: 15px;"><?php esc_html_e('Enter your mobile number to receive a secure OTP.', 'allure-clinics'); ?></p>
                
                <div id="ac-portal-step-phone">
                    <input type="tel" id="ac_portal_mobile" class="ac-input-premium" required style="margin-bottom: 20px;" placeholder="+966500000000">
                    <button id="ac_portal_request_otp" class="ac-btn-premium">
                        <?php esc_html_e('Send OTP', 'allure-clinics'); ?>
                    </button>
                </div>

                <div id="ac-portal-step-otp" style="display:none;">
                    <div id="ac_portal_test_banner" style="display:none; background: #fff3cd; color: #856404; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 14px; border: 1px solid #ffeeba;">
                        TEST MODE — OTP: <span id="ac_portal_test_otp_code">...</span>
                    </div>
                    <p style="color:#27ae60; font-weight:600; font-size:14px; text-align:center; margin-bottom: 20px;" id="ac_portal_otp_sent_msg"></p>
                    <input type="text" id="ac_portal_otp" class="ac-input-premium" required style="margin-bottom: 20px; text-align:center; letter-spacing: 8px; font-size: 22px; font-weight: 600;" placeholder="123456" maxlength="6">
                    <button id="ac_portal_verify_otp" class="ac-btn-premium">
                        <?php esc_html_e('Login', 'allure-clinics'); ?>
                    </button>
                </div>
                
                <div id="ac-portal-msg" style="margin-top: 20px; padding: 15px; border-radius: 8px; font-weight: 500; display:none;"></div>
            </div>

            <!-- Dashboard State -->
            <div id="ac-portal-dashboard" style="display:none; animation: acFadeIn 0.4s ease-out forwards;">
                
                <div style="background: #ffffff; padding: 25px 30px; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.06); display:flex; justify-content:space-between; align-items:center; margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <img src="https://shoaib.standardtouch.com/allureclinics/wp-content/uploads/2026/07/allure-logo-1.png" alt="Allure Clinics Logo" style="max-height: 50px;">
                        <div>
                            <h2 style="margin:0; color: #2c3e50; font-weight: 600; font-size: 24px;"><?php esc_html_e('Welcome, ', 'allure-clinics'); ?><span id="ac_portal_patient_name" style="color: #5ab0a9;"></span></h2>
                            <span style="color: #7f8c8d; font-size: 14px;">Patient Dashboard</span>
                        </div>
                    </div>
                    <button id="ac_portal_logout" style="padding: 10px 20px; background: #ffebee; color: #e74c3c; border: 1px solid #ffcdd2; border-radius: 8px; cursor: pointer; font-family: inherit; font-weight: 600; transition: all 0.2s;"><?php esc_html_e('Logout', 'allure-clinics'); ?></button>
                </div>

                <!-- Tabs Navigation -->
                <div style="display: flex; gap: 15px; margin-bottom: 25px; border-bottom: 2px solid #edf2f7; padding-bottom: 15px; overflow-x: auto;">
                    <button class="ac-portal-tab active" data-target="tab-appointments" style="background:none; border:none; color:#5ab0a9; font-weight:600; font-size:16px; font-family:inherit; cursor:pointer; padding: 5px 10px; border-bottom: 3px solid #5ab0a9;">Appointments</button>
                    <button class="ac-portal-tab" data-target="tab-invoices" style="background:none; border:none; color:#7f8c8d; font-weight:600; font-size:16px; font-family:inherit; cursor:pointer; padding: 5px 10px; border-bottom: 3px solid transparent;">Invoices</button>
                    <button class="ac-portal-tab" data-target="tab-medical-history" style="background:none; border:none; color:#7f8c8d; font-weight:600; font-size:16px; font-family:inherit; cursor:pointer; padding: 5px 10px; border-bottom: 3px solid transparent;">Medical History</button>
                </div>

                <!-- Tab: Appointments -->
                <div id="tab-appointments" class="ac-portal-tab-content">
                    <div style="background: #ffffff; padding: 30px; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.06); margin-bottom: 25px;">
                        <h3 style="margin-top:0; color: #2c3e50; font-weight: 600; font-size: 20px; border-bottom: 2px solid #5ab0a9; padding-bottom: 10px; display: inline-block; margin-bottom: 20px;"><?php esc_html_e('Upcoming Appointments', 'allure-clinics'); ?></h3>
                        <div id="ac_portal_upcoming"></div>
                    </div>

                    <div style="background: #ffffff; padding: 30px; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.06);">
                        <h3 style="margin-top:0; color: #2c3e50; font-weight: 600; font-size: 20px; border-bottom: 2px solid #bdc3c7; padding-bottom: 10px; display: inline-block; margin-bottom: 20px;"><?php esc_html_e('Past Appointments', 'allure-clinics'); ?></h3>
                        <div id="ac_portal_past"></div>
                    </div>
                </div>

                <!-- Tab: Invoices -->
                <div id="tab-invoices" class="ac-portal-tab-content" style="display:none;">
                    <div style="background: #ffffff; padding: 40px; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.06); text-align: center;">
                        <div id="ac_portal_invoices_loading" style="color: #7f8c8d;"><?php esc_html_e('Loading invoices...', 'allure-clinics'); ?></div>
                        <div id="ac_portal_invoices_content" style="display:none;"></div>
                    </div>
                </div>

                <!-- Tab: Medical History -->
                <div id="tab-medical-history" class="ac-portal-tab-content" style="display:none;">
                    <div style="background: #ffffff; padding: 40px; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.06); text-align: center;">
                        <div id="ac_portal_history_loading" style="color: #7f8c8d;"><?php esc_html_e('Loading medical history...', 'allure-clinics'); ?></div>
                        <div id="ac_portal_history_content" style="display:none;"></div>
                    </div>
                </div>

            </div>

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
            
            @keyframes acFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            
            #ac_portal_logout:hover { background: #e74c3c !important; color: #fff !important; }
        </style>

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
                otpSentMsg: document.getElementById('ac_portal_otp_sent_msg'),
                testBanner: document.getElementById('ac_portal_test_banner'),
                testOtpCode: document.getElementById('ac_portal_test_otp_code'),
                
                tabs: document.querySelectorAll('.ac-portal-tab'),
                tabContents: document.querySelectorAll('.ac-portal-tab-content'),
                
                invoicesLoading: document.getElementById('ac_portal_invoices_loading'),
                invoicesContent: document.getElementById('ac_portal_invoices_content'),
                
                historyLoading: document.getElementById('ac_portal_history_loading'),
                historyContent: document.getElementById('ac_portal_history_content')
            };

            function showMsg(text, isError) {
                dom.msg.style.display = 'block';
                dom.msg.style.backgroundColor = isError ? '#f8d7da' : '#d4edda';
                dom.msg.style.color = isError ? '#721c24' : '#155724';
                dom.msg.innerText = text;
            }

            // Tab Switching Logic
            dom.tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Reset all tabs
                    dom.tabs.forEach(t => {
                        t.classList.remove('active');
                        t.style.color = '#7f8c8d';
                        t.style.borderBottomColor = 'transparent';
                    });
                    dom.tabContents.forEach(c => c.style.display = 'none');
                    
                    // Activate clicked tab
                    this.classList.add('active');
                    this.style.color = '#5ab0a9';
                    this.style.borderBottomColor = '#5ab0a9';
                    document.getElementById(this.dataset.target).style.display = 'block';
                });
            });

            function fetchDashboard() {
                dom.login.style.display = 'none';
                dom.dashboard.style.display = 'block';

                // Fetch Profile
                fetch('<?php echo esc_url(rest_url('allure/v1/patient/profile')); ?>', {
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
                fetch('<?php echo esc_url(rest_url('allure/v1/patient/appointments')); ?>', {
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
                                <div style="padding: 20px; border: 1px solid #edf2f7; border-radius: 12px; margin-bottom: 15px; display:flex; justify-content:space-between; align-items:center; background: #fdfbfb;">
                                    <div>
                                        <strong style="font-size:18px; color: #2c3e50;">${app.date} at ${app.start_time}</strong><br>
                                        <span style="color:#7f8c8d; margin-top: 5px; display: inline-block;">Dr. ${app.doctor_name} &bull; ${app.branch_name}</span>
                                    </div>
                                    <div>
                                        <span style="background: rgba(90,176,169,0.1); color: #5ab0a9; padding: 6px 12px; border-radius: 20px; font-size:13px; font-weight: 600; text-transform: uppercase;">${app.status}</span>
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

                // Fetch Invoices
                fetch('<?php echo esc_url(rest_url('allure/v1/patient/invoices')); ?>', {
                    headers: { 'Authorization': 'Bearer ' + sessionToken }
                })
                .then(res => res.json())
                .then(data => {
                    dom.invoicesLoading.style.display = 'none';
                    dom.invoicesContent.style.display = 'block';
                    
                    if (data && data.length > 0) {
                        // Render invoices
                        dom.invoicesContent.innerHTML = '<p>Invoices loaded.</p>'; // Extend later
                    } else {
                        dom.invoicesContent.innerHTML = `
                            <div style="padding: 20px; border: 1px dashed #ccd0d4; border-radius: 12px; background: #f8f9fa;">
                                <p style="color: #7f8c8d; font-size: 15px; margin: 0;">Payment history will appear here once your clinic's billing system is connected.</p>
                            </div>
                        `;
                    }
                }).catch(() => {
                    dom.invoicesLoading.style.display = 'none';
                });

                // Fetch Medical History
                fetch('<?php echo esc_url(rest_url('allure/v1/patient/medical-history')); ?>', {
                    headers: { 'Authorization': 'Bearer ' + sessionToken }
                })
                .then(res => res.json())
                .then(data => {
                    dom.historyLoading.style.display = 'none';
                    dom.historyContent.style.display = 'block';
                    
                    // Check if any category has data
                    const hasData = data && (
                        (data.consultations && data.consultations.length > 0) ||
                        (data.treatments && data.treatments.length > 0) ||
                        (data.prescriptions && data.prescriptions.length > 0) ||
                        (data.labs && data.labs.length > 0)
                    );

                    if (hasData) {
                        dom.historyContent.innerHTML = '<p>Medical history loaded.</p>'; // Extend later
                    } else {
                        dom.historyContent.innerHTML = `
                            <div style="padding: 20px; border: 1px dashed #ccd0d4; border-radius: 12px; background: #f8f9fa;">
                                <p style="color: #7f8c8d; font-size: 15px; margin: 0;">Medical records and treatment history will appear here once your clinic's EMR system is connected.</p>
                            </div>
                        `;
                    }
                }).catch(() => {
                    dom.historyLoading.style.display = 'none';
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
                dom.testBanner.style.display = 'none';
            }

            if (sessionToken) {
                fetchDashboard();
            }

            dom.btnRequest.addEventListener('click', function() {
                currentMobile = dom.mobile.value.trim();
                if (!currentMobile) return showMsg('Please enter mobile number.', true);
                
                dom.btnRequest.disabled = true;
                dom.btnRequest.innerText = '...';

                fetch('<?php echo esc_url(rest_url('allure/v1/auth/otp/request')); ?>', {
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
                        
                        if (res.body.test_mode) {
                            dom.testBanner.style.display = 'block';
                            dom.testOtpCode.innerText = 'fetching...';
                            fetch('<?php echo esc_url(rest_url('allure/v1/admin/test-otp')); ?>?mobile=' + encodeURIComponent(currentMobile))
                                .then(r => r.json())
                                .then(d => {
                                    if (d.otp) {
                                        dom.testOtpCode.innerText = d.otp;
                                    } else {
                                        dom.testOtpCode.innerText = 'Error fetching OTP';
                                    }
                                }).catch(() => {
                                    dom.testOtpCode.innerText = 'Error fetching OTP';
                                });
                        } else {
                            dom.testBanner.style.display = 'none';
                        }
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

                fetch('<?php echo esc_url(rest_url('allure/v1/auth/otp/verify')); ?>', {
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
