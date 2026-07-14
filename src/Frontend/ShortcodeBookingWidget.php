<?php

namespace AllureClinics\Frontend;

class ShortcodeBookingWidget {

    public function __construct() {
        add_shortcode('allure_booking', [$this, 'render']);
    }

    public function render($atts) {
        ob_start();
        ?>
        <div class="ac-premium-widget" style="max-width: 800px; margin: 40px auto; background: #ffffff; border-radius: 20px; box-shadow: 0 12px 40px rgba(0,0,0,0.08); overflow: hidden; font-family: 'Outfit', 'Inter', sans-serif;">
            
            <!-- Branding Header -->
            <div style="background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%); padding: 30px 20px; text-align: center; border-bottom: 1px solid #eaeaea;">
                <img src="https://shoaib.standardtouch.com/allureclinics/wp-content/uploads/2026/07/allure-logo-1.png" alt="Allure Clinics Logo" style="max-height: 70px; margin-bottom: 10px; display: inline-block;">
                <h2 style="margin: 0; color: #2c3e50; font-weight: 600; font-size: 28px; letter-spacing: -0.5px;">Allure Clinics</h2>
                <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 15px;"><?php esc_html_e('Book your consultation', 'allure-clinics'); ?></p>
            </div>

            <div style="padding: 40px 30px;">
                <!-- Step 1: Branch & Doctor -->
                <div id="ac_step_1" class="ac-step-container">
                    <h3 style="margin-top:0; color: #2c3e50; font-weight: 600; font-size: 20px; border-bottom: 2px solid #5ab0a9; padding-bottom: 10px; display: inline-block;"><?php esc_html_e('1. Select Provider', 'allure-clinics'); ?></h3>
                    
                    <div style="margin-bottom: 25px; margin-top: 15px;">
                        <label style="display: block; font-weight: 500; color: #34495e; margin-bottom: 8px;"><?php esc_html_e('Choose Branch', 'allure-clinics'); ?></label>
                        <select id="ac_branch_select" class="ac-input-premium">
                            <option value=""><?php esc_html_e('Loading branches...', 'allure-clinics'); ?></option>
                        </select>
                    </div>

                    <div id="ac_doctor_list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                        <!-- Doctors injected here -->
                    </div>
                </div>

                <!-- Step 2: Date & Slot -->
                <div id="ac_step_2" class="ac-step-container" style="display:none;">
                    <button class="ac-back-btn" data-target="ac_step_1" style="background:none; border:none; color:#5ab0a9; cursor:pointer; padding:0; margin-bottom:20px; font-weight: 500; font-size: 15px; transition: color 0.2s;">&larr; <?php esc_html_e('Back to Providers', 'allure-clinics'); ?></button>
                    <h3 style="margin-top:0; color: #2c3e50; font-weight: 600; font-size: 20px; border-bottom: 2px solid #5ab0a9; padding-bottom: 10px; display: inline-block;"><?php esc_html_e('2. Select Time', 'allure-clinics'); ?></h3>
                    
                    <div style="display:flex; align-items:center; gap: 20px; margin: 20px 0; padding: 20px; background: #f8f9fa; border-radius: 12px; border: 1px solid #edf2f7;">
                        <img id="ac_selected_doc_photo" src="" style="width: 60px; height: 60px; border-radius: 50%; object-fit:cover;">
                        <div>
                            <strong style="font-size: 18px;" id="ac_selected_doc_name"></strong><br>
                            <span style="color:#666;" id="ac_selected_doc_spec"></span>
                        </div>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-weight: 500; color: #34495e; margin-bottom: 8px;"><?php esc_html_e('Select Date', 'allure-clinics'); ?></label>
                        <input type="date" id="ac_date_select" class="ac-input-premium">
                    </div>

                    <div id="ac_slot_list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px; margin-bottom: 20px;">
                        <!-- Slots injected here -->
                    </div>
                </div>

                <!-- Step 3: Patient Details & OTP -->
                <div id="ac_step_3" class="ac-step-container" style="display:none;">
                    <button class="ac-back-btn" data-target="ac_step_2" style="background:none; border:none; color:#5ab0a9; cursor:pointer; padding:0; margin-bottom:20px; font-weight: 500; font-size: 15px; transition: color 0.2s;">&larr; <?php esc_html_e('Back to Time Selection', 'allure-clinics'); ?></button>
                    <h3 style="margin-top:0; color: #2c3e50; font-weight: 600; font-size: 20px; border-bottom: 2px solid #5ab0a9; padding-bottom: 10px; display: inline-block;"><?php esc_html_e('3. Your Details', 'allure-clinics'); ?></h3>
                    
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #edf2f7; text-align: center;">
                        <strong style="color: #7f8c8d; font-weight: 500; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;"><?php esc_html_e('Appointment Summary', 'allure-clinics'); ?></strong><br>
                        <span id="ac_summary_doc" style="font-size: 18px; color: #2c3e50; font-weight: 600; display: block; margin-top: 5px;"></span>
                        <span id="ac_summary_time" style="color:#5ab0a9; font-weight:600; font-size: 16px;"></span>
                    </div>

                    <div id="ac_booking_form_wrapper">
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 500; color: #34495e; margin-bottom: 8px;"><?php esc_html_e('Full Name', 'allure-clinics'); ?></label>
                            <input type="text" id="ac_patient_name" class="ac-input-premium">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-weight: 500; color: #34495e; margin-bottom: 8px;"><?php esc_html_e('Mobile Number', 'allure-clinics'); ?></label>
                            <input type="tel" id="ac_patient_mobile" class="ac-input-premium" placeholder="+966500000000">
                        </div>
                        <div style="margin-bottom: 25px;">
                            <label style="display: block; font-weight: 500; color: #34495e; margin-bottom: 8px;"><?php esc_html_e('Email (Optional)', 'allure-clinics'); ?></label>
                            <input type="email" id="ac_patient_email" class="ac-input-premium">
                        </div>
                        <button id="ac_btn_request_otp" class="ac-btn-premium">
                            <?php esc_html_e('Continue to Verification', 'allure-clinics'); ?>
                        </button>
                    </div>

                    <div id="ac_otp_wrapper" style="display:none; text-align:center;">
                        <p style="color:#27ae60; font-weight:600; margin-bottom: 20px; font-size: 15px;" id="ac_otp_sent_msg"></p>
                        <input type="text" id="ac_otp_code" class="ac-input-premium" style="max-width:250px; margin: 0 auto 20px auto; text-align:center; letter-spacing: 8px; font-size: 22px; font-weight: 600;" placeholder="123456" maxlength="6">
                        <button id="ac_btn_confirm_booking" class="ac-btn-premium">
                            <?php esc_html_e('Confirm Booking', 'allure-clinics'); ?>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Success -->
                <div id="ac_step_4" class="ac-step-container" style="display:none; text-align:center; padding: 40px 20px;">
                    <div style="width: 90px; height: 90px; background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); color:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:45px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(46,204,113,0.3);">&check;</div>
                    <h2 style="color:#27ae60; font-weight: 600; font-size: 28px; margin-bottom: 10px;"><?php esc_html_e('Booking Confirmed!', 'allure-clinics'); ?></h2>
                    <p style="color: #7f8c8d; font-size: 16px; margin-bottom: 30px;"><?php esc_html_e('Thank you for choosing Allure Clinics. A confirmation email has been sent to you.', 'allure-clinics'); ?></p>
                    <div id="ac_final_summary" style="font-weight:600; background:#f8f9fa; color: #2c3e50; padding:20px; border-radius:12px; display:inline-block; border: 1px solid #edf2f7; font-size: 18px;"></div>
                </div>

                <div id="ac_global_msg" style="display:none; margin-top: 20px; padding: 15px; border-radius: 8px; font-weight: 500; text-align: center;"></div>
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

            .ac-doc-card { background: #fff; border: 1px solid #edf2f7; border-radius: 16px; padding: 25px 20px; text-align: center; cursor: pointer; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
            .ac-doc-card:hover { border-color: #5ab0a9; box-shadow: 0 10px 25px rgba(90,176,169,0.15); transform: translateY(-5px); }
            .ac-doc-card img { border: 3px solid #fdfbfb; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
            
            .ac-slot-btn { padding: 12px; background: #fdfbfb; border: 1px solid #edf2f7; color: #2c3e50; border-radius: 10px; cursor: pointer; text-align:center; font-weight:600; transition: all 0.2s; font-size: 15px; }
            .ac-slot-btn:hover { background: #f0f7f7; border-color: #5ab0a9; color: #5ab0a9; }
            .ac-slot-btn.selected { background: #5ab0a9; color: #fff; border-color: #5ab0a9; box-shadow: 0 4px 12px rgba(90,176,169,0.3); }
            
            .ac-step-container { animation: acFadeIn 0.4s ease-out forwards; }
            @keyframes acFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
            
            .ac-back-btn:hover { color: #469a93; }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            let state = {
                doctors: [],
                selectedDoctor: null,
                selectedSlot: null,
                sessionToken: localStorage.getItem('ac_session_token') || null
            };

            const dom = {
                step1: document.getElementById('ac_step_1'),
                step2: document.getElementById('ac_step_2'),
                step3: document.getElementById('ac_step_3'),
                step4: document.getElementById('ac_step_4'),
                branchSelect: document.getElementById('ac_branch_select'),
                doctorList: document.getElementById('ac_doctor_list'),
                dateSelect: document.getElementById('ac_date_select'),
                slotList: document.getElementById('ac_slot_list'),
                msg: document.getElementById('ac_global_msg')
            };

            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            dom.dateSelect.value = today;
            dom.dateSelect.min = today;

            function showMsg(text, isError) {
                dom.msg.style.display = 'block';
                dom.msg.style.backgroundColor = isError ? '#f8d7da' : '#d4edda';
                dom.msg.style.color = isError ? '#721c24' : '#155724';
                dom.msg.innerText = text;
            }
            function hideMsg() { dom.msg.style.display = 'none'; }

            function showStep(stepNum) {
                [1,2,3,4].forEach(n => document.getElementById('ac_step_'+n).style.display = 'none');
                document.getElementById('ac_step_'+stepNum).style.display = 'block';
                hideMsg();
            }

            // Back buttons
            document.querySelectorAll('.ac-back-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const target = e.target.getAttribute('data-target');
                    [1,2,3,4].forEach(n => document.getElementById('ac_step_'+n).style.display = 'none');
                    document.getElementById(target).style.display = 'block';
                    hideMsg();
                });
            });

            // 1. Fetch Branches & Doctors
            Promise.all([
                fetch('<?php echo esc_url(rest_url('allure/v1/branches')); ?>').then(res => res.json()),
                fetch('<?php echo esc_url(rest_url('allure/v1/doctors')); ?>').then(res => res.json())
            ]).then(([branches, doctors]) => {
                state.doctors = doctors;
                
                dom.branchSelect.innerHTML = '<option value=""><?php esc_html_e('All Branches', 'allure-clinics'); ?></option>';
                branches.forEach(branch => {
                    dom.branchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
                });

                renderDoctors(doctors);
            });

            dom.branchSelect.addEventListener('change', (e) => {
                const branchId = e.target.value;
                const filtered = branchId ? state.doctors.filter(d => d.branch_id == branchId) : state.doctors;
                renderDoctors(filtered);
            });

            function renderDoctors(docs) {
                dom.doctorList.innerHTML = '';
                if(docs.length === 0) {
                    dom.doctorList.innerHTML = '<p>No doctors found.</p>';
                    return;
                }

                docs.forEach(doc => {
                    let specs = Array.isArray(doc.specialties) ? doc.specialties.join(', ') : doc.specialties;
                    let photo = doc.photo_url || 'https://via.placeholder.com/150';
                    const div = document.createElement('div');
                    div.className = 'ac-doc-card';
                    div.innerHTML = `
                        <img src="${photo}" style="width:90px; height:90px; border-radius:50%; object-fit:cover; margin-bottom:15px;">
                        <h4 style="margin:0 0 8px 0; color: #2c3e50; font-size: 18px; font-weight: 600;">Dr. ${doc.name}</h4>
                        <div style="font-size:13px; color:#7f8c8d; margin-bottom:15px; line-height: 1.4;">${specs}</div>
                        <button class="ac-btn-premium" style="padding: 10px; font-size: 14px;"><?php esc_html_e('Select Provider', 'allure-clinics'); ?></button>
                    `;
                    div.addEventListener('click', () => selectDoctor(doc));
                    dom.doctorList.appendChild(div);
                });
            }

            function selectDoctor(doc) {
                state.selectedDoctor = doc;
                document.getElementById('ac_selected_doc_photo').src = doc.photo_url || 'https://via.placeholder.com/150';
                document.getElementById('ac_selected_doc_name').innerText = 'Dr. ' + doc.name;
                document.getElementById('ac_selected_doc_spec').innerText = Array.isArray(doc.specialties) ? doc.specialties.join(', ') : doc.specialties;
                
                showStep(2);
                fetchSlots();
            }

            dom.dateSelect.addEventListener('change', fetchSlots);

            function fetchSlots() {
                const date = dom.dateSelect.value;
                if(!date || !state.selectedDoctor) return;

                dom.slotList.innerHTML = '<?php esc_html_e('Loading slots...', 'allure-clinics'); ?>';

                fetch(`<?php echo esc_url(rest_url('allure/v1/doctors/')); ?>${state.selectedDoctor.id}/schedule?date=${date}`)
                .then(res => res.json())
                .then(data => {
                    dom.slotList.innerHTML = '';
                    if (!data.schedule || data.schedule.length === 0) {
                        dom.slotList.innerHTML = '<p style="grid-column: 1 / -1; color:#777;"><?php esc_html_e('No slots available on this date.', 'allure-clinics'); ?></p>';
                        return;
                    }

                    data.schedule.forEach(slot => {
                        const div = document.createElement('div');
                        div.className = 'ac-slot-btn';
                        div.innerText = slot.start_time.substring(0, 5); // HH:MM
                        div.addEventListener('click', () => {
                            document.querySelectorAll('.ac-slot-btn').forEach(el => el.classList.remove('selected'));
                            div.classList.add('selected');
                            
                            // Select slot and proceed
                            state.selectedSlot = slot;
                            document.getElementById('ac_summary_doc').innerText = 'Dr. ' + state.selectedDoctor.name;
                            document.getElementById('ac_summary_time').innerText = slot.date + ' at ' + slot.start_time;
                            showStep(3);

                            // If already logged in, skip OTP step
                            if (state.sessionToken) {
                                document.getElementById('ac_booking_form_wrapper').style.display = 'none';
                                document.getElementById('ac_otp_wrapper').style.display = 'block';
                                document.getElementById('ac_otp_sent_msg').innerText = '<?php esc_html_e('You are logged in.', 'allure-clinics'); ?>';
                                document.getElementById('ac_otp_code').style.display = 'none'; // Hide OTP input
                            } else {
                                document.getElementById('ac_booking_form_wrapper').style.display = 'block';
                                document.getElementById('ac_otp_wrapper').style.display = 'none';
                            }
                        });
                        dom.slotList.appendChild(div);
                    });
                });
            }

            // Request OTP
            document.getElementById('ac_btn_request_otp').addEventListener('click', () => {
                const mobile = document.getElementById('ac_patient_mobile').value.trim();
                const name = document.getElementById('ac_patient_name').value.trim();

                if (!mobile || !name) {
                    showMsg('<?php esc_html_e('Name and Mobile are required.', 'allure-clinics'); ?>', true);
                    return;
                }

                const btn = document.getElementById('ac_btn_request_otp');
                btn.disabled = true;
                btn.innerText = '...';

                fetch('<?php echo esc_url(rest_url('allure/v1/auth/otp/request')); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mobile: mobile })
                })
                .then(res => res.json().then(data => ({status: res.status, body: data})))
                .then(res => {
                    if (res.status === 200) {
                        hideMsg();
                        document.getElementById('ac_booking_form_wrapper').style.display = 'none';
                        document.getElementById('ac_otp_wrapper').style.display = 'block';
                        document.getElementById('ac_otp_sent_msg').innerText = res.body.message;
                    } else {
                        showMsg(res.body.error, true);
                    }
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerText = '<?php esc_html_e('Continue to Verification', 'allure-clinics'); ?>';
                });
            });

            // Confirm Booking
            document.getElementById('ac_btn_confirm_booking').addEventListener('click', async () => {
                const btn = document.getElementById('ac_btn_confirm_booking');
                btn.disabled = true;
                btn.innerText = '<?php esc_html_e('Processing...', 'allure-clinics'); ?>';

                try {
                    // If not logged in, verify OTP first
                    if (!state.sessionToken) {
                        const mobile = document.getElementById('ac_patient_mobile').value.trim();
                        const otp = document.getElementById('ac_otp_code').value.trim();
                        const name = document.getElementById('ac_patient_name').value.trim();
                        const email = document.getElementById('ac_patient_email').value.trim();

                        if (!otp) throw new Error('<?php esc_html_e('Please enter OTP.', 'allure-clinics'); ?>');

                        const verifyRes = await fetch('<?php echo esc_url(rest_url('allure/v1/auth/otp/verify')); ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ mobile, otp, name, email })
                        });
                        const verifyData = await verifyRes.json();
                        
                        if (verifyRes.status !== 200) throw new Error(verifyData.error);
                        
                        state.sessionToken = verifyData.session_token;
                        localStorage.setItem('ac_session_token', state.sessionToken);
                    }

                    // Book Slot
                    const bookRes = await fetch('<?php echo esc_url(rest_url('allure/v1/appointments')); ?>', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + state.sessionToken
                        },
                        body: JSON.stringify({ slot_id: state.selectedSlot.id })
                    });
                    const bookData = await bookRes.json();

                    if (bookRes.status === 201) {
                        showStep(4);
                        document.getElementById('ac_final_summary').innerText = 'Dr. ' + state.selectedDoctor.name + ' - ' + state.selectedSlot.date + ' @ ' + state.selectedSlot.start_time;
                    } else {
                        throw new Error(bookData.error || 'Failed to book appointment.');
                    }
                } catch (err) {
                    showMsg(err.message, true);
                } finally {
                    btn.disabled = false;
                    btn.innerText = '<?php esc_html_e('Confirm Booking', 'allure-clinics'); ?>';
                }
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
