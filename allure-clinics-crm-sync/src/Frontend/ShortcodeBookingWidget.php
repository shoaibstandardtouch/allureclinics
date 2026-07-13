<?php

namespace AllureClinics\Frontend;

class ShortcodeBookingWidget {

    public function __construct() {
        add_shortcode('allure_booking', [$this, 'render']);
    }

    public function render($atts) {
        ob_start();
        ?>
        <div class="ac-booking-widget" style="max-width: 800px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; font-family: sans-serif;">
            
            <div style="background: #2271b1; color: #fff; padding: 20px; text-align: center;">
                <h2 style="margin: 0; color: #fff;"><?php esc_html_e('Book an Appointment', 'allure-clinics'); ?></h2>
            </div>

            <div style="padding: 20px;">
                <!-- Step 1: Branch & Doctor -->
                <div id="ac_step_1">
                    <h3 style="margin-top:0;"><?php esc_html_e('1. Select Provider', 'allure-clinics'); ?></h3>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Select Branch', 'allure-clinics'); ?></label>
                        <select id="ac_branch_select" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value=""><?php esc_html_e('Loading branches...', 'allure-clinics'); ?></option>
                        </select>
                    </div>

                    <div id="ac_doctor_list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                        <!-- Doctors injected here -->
                    </div>
                </div>

                <!-- Step 2: Date & Slot -->
                <div id="ac_step_2" style="display:none;">
                    <button class="ac-back-btn" data-target="ac_step_1" style="background:none; border:none; color:#2271b1; cursor:pointer; padding:0; margin-bottom:15px; text-decoration:underline;">&larr; <?php esc_html_e('Back to Providers', 'allure-clinics'); ?></button>
                    <h3 style="margin-top:0;"><?php esc_html_e('2. Select Time', 'allure-clinics'); ?></h3>
                    
                    <div style="display:flex; align-items:center; gap: 15px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                        <img id="ac_selected_doc_photo" src="" style="width: 60px; height: 60px; border-radius: 50%; object-fit:cover;">
                        <div>
                            <strong style="font-size: 18px;" id="ac_selected_doc_name"></strong><br>
                            <span style="color:#666;" id="ac_selected_doc_spec"></span>
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Select Date', 'allure-clinics'); ?></label>
                        <input type="date" id="ac_date_select" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>

                    <div id="ac_slot_list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin-bottom: 20px;">
                        <!-- Slots injected here -->
                    </div>
                </div>

                <!-- Step 3: Patient Details & OTP -->
                <div id="ac_step_3" style="display:none;">
                    <button class="ac-back-btn" data-target="ac_step_2" style="background:none; border:none; color:#2271b1; cursor:pointer; padding:0; margin-bottom:15px; text-decoration:underline;">&larr; <?php esc_html_e('Back to Time Selection', 'allure-clinics'); ?></button>
                    <h3 style="margin-top:0;"><?php esc_html_e('3. Your Details', 'allure-clinics'); ?></h3>
                    
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                        <strong><?php esc_html_e('Appointment Summary', 'allure-clinics'); ?></strong><br>
                        <span id="ac_summary_doc"></span><br>
                        <span id="ac_summary_time" style="color:#2271b1; font-weight:bold;"></span>
                    </div>

                    <div id="ac_booking_form_wrapper">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Full Name', 'allure-clinics'); ?></label>
                            <input type="text" id="ac_patient_name" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Mobile Number', 'allure-clinics'); ?></label>
                            <input type="tel" id="ac_patient_mobile" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;" placeholder="+966500000000">
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e('Email (Optional)', 'allure-clinics'); ?></label>
                            <input type="email" id="ac_patient_email" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                        <button id="ac_btn_request_otp" style="width: 100%; padding: 12px; background: #2271b1; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                            <?php esc_html_e('Continue to Verification', 'allure-clinics'); ?>
                        </button>
                    </div>

                    <div id="ac_otp_wrapper" style="display:none; text-align:center;">
                        <p style="color:#007017; font-weight:bold;" id="ac_otp_sent_msg"></p>
                        <input type="text" id="ac_otp_code" style="width: 100%; max-width:200px; padding: 12px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 15px; text-align:center; letter-spacing: 5px; font-size: 18px;" placeholder="123456" maxlength="6">
                        <button id="ac_btn_confirm_booking" style="width: 100%; padding: 12px; background: #007017; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">
                            <?php esc_html_e('Confirm Booking', 'allure-clinics'); ?>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Success -->
                <div id="ac_step_4" style="display:none; text-align:center; padding: 40px 20px;">
                    <div style="width: 80px; height: 80px; background: #007017; color:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:40px; margin-bottom: 20px;">&check;</div>
                    <h2 style="color:#007017;"><?php esc_html_e('Booking Confirmed!', 'allure-clinics'); ?></h2>
                    <p><?php esc_html_e('Thank you for choosing Allure Clinics. A confirmation email has been sent to you.', 'allure-clinics'); ?></p>
                    <p id="ac_final_summary" style="font-weight:bold; background:#f9f9f9; padding:15px; border-radius:4px; display:inline-block;"></p>
                </div>

                <div id="ac_global_msg" style="display:none; margin-top: 15px; padding: 10px; border-radius: 4px;"></div>
            </div>
        </div>

        <style>
            .ac-doc-card { border: 1px solid #eee; border-radius: 8px; padding: 15px; text-align: center; cursor: pointer; transition: all 0.2s; }
            .ac-doc-card:hover { border-color: #2271b1; box-shadow: 0 4px 8px rgba(34,113,177,0.1); transform: translateY(-2px); }
            .ac-slot-btn { padding: 10px; background: #f0f6fc; border: 1px solid #c8dcf0; color: #2271b1; border-radius: 4px; cursor: pointer; text-align:center; font-weight:bold; }
            .ac-slot-btn:hover { background: #2271b1; color: #fff; }
            .ac-slot-btn.selected { background: #2271b1; color: #fff; border-color: #2271b1; box-shadow: 0 0 0 2px rgba(34,113,177,0.3); }
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

            // 1. Fetch Doctors & Branches
            fetch('/wp-json/allure/v1/doctors')
            .then(res => res.json())
            .then(data => {
                state.doctors = data;
                
                // Extract unique branches
                const branches = new Map();
                data.forEach(d => {
                    if (!branches.has(d.branch_id)) {
                        branches.set(d.branch_id, d.branch_name);
                    }
                });

                dom.branchSelect.innerHTML = '<option value="">All Branches</option>';
                branches.forEach((name, id) => {
                    dom.branchSelect.innerHTML += `<option value="${id}">${name}</option>`;
                });

                renderDoctors(data);
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
                        <img src="${photo}" style="width:80px; height:80px; border-radius:50%; object-fit:cover; margin-bottom:10px;">
                        <h4 style="margin:0 0 5px 0;">Dr. ${doc.name}</h4>
                        <div style="font-size:12px; color:#666; margin-bottom:10px;">${specs}</div>
                        <button class="button" style="width:100%;"><?php esc_html_e('Select', 'allure-clinics'); ?></button>
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

                fetch(`/wp-json/allure/v1/doctors/${state.selectedDoctor.id}/schedule?date=${date}`)
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

                fetch('/wp-json/allure/v1/auth/otp/request', {
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

                        const verifyRes = await fetch('/wp-json/allure/v1/auth/otp/verify', {
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
                    const bookRes = await fetch('/wp-json/allure/v1/appointments', {
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
