<?php

namespace AllureClinics\Admin;

class DemoSeeder {

    public static function seed(): void {
        global $wpdb;

        // 1. Seed Branches
        $branches = [
            [
                'name' => 'Allure Clinics — North Branch',
                'name_ar' => 'عيادات ألور — فرع الشمال',
                'address' => 'Al Malqa District, Riyadh 13521',
                'phone' => '+966500000001',
                'crm_id' => null,
                'sync_status' => 'synced',
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ],
            [
                'name' => 'Allure Clinics — Olaya Branch',
                'name_ar' => 'عيادات ألور — فرع العليا',
                'address' => 'Olaya Street, Riyadh 12211',
                'phone' => '+966500000002',
                'crm_id' => null,
                'sync_status' => 'synced',
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ]
        ];

        $branch_ids = [];
        foreach ($branches as $branch) {
            $wpdb->insert("{$wpdb->prefix}ac_branches", $branch);
            $branch_ids[] = $wpdb->insert_id;
        }

        // 2. Seed Doctors
        $doctors = [
            [
                'branch_id' => $branch_ids[0],
                'name' => 'Dr. Layan Al-Fayez',
                'name_ar' => 'د. ليان الفايز',
                'specialties' => wp_json_encode(['Botox/Injectables', 'Dermal Fillers']),
                'bio' => 'Dr. Layan is a leading aesthetic physician specializing in advanced non-surgical facial rejuvenation. With over 8 years of experience, she is renowned for her natural-looking results in Botox and dermal fillers.',
                'bio_ar' => 'د. ليان هي طبيبة تجميل رائدة متخصصة في تجديد شباب الوجه بدون جراحة. تتمتع بخبرة تزيد عن 8 سنوات، وتشتهر بنتائجها الطبيعية في حقن البوتوكس والفيلر.',
                'years_experience' => 8,
                'qualifications' => wp_json_encode(['MD, King Saud University', 'Board Certified in Aesthetic Medicine']),
                'crm_id' => null,
                'sync_status' => 'synced',
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ],
            [
                'branch_id' => $branch_ids[0],
                'name' => 'Dr. Omar Tariq',
                'name_ar' => 'د. عمر طارق',
                'specialties' => wp_json_encode(['Laser Resurfacing', 'Skin Tightening']),
                'bio' => 'Dr. Omar brings unparalleled expertise in cutting-edge laser therapies and radiofrequency skin tightening. He focuses on tailored treatment plans for hyperpigmentation and skin laxity.',
                'bio_ar' => 'يقدم د. عمر خبرة لا مثيل لها في علاجات الليزر المتقدمة وشد الجلد بالترددات الراديوية. يركز على خطط العلاج المصممة خصيصًا للتصبغات وترهل الجلد.',
                'years_experience' => 12,
                'qualifications' => wp_json_encode(['Fellowship in Dermatologic Laser Surgery']),
                'crm_id' => null,
                'sync_status' => 'synced',
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ],
            [
                'branch_id' => $branch_ids[1],
                'name' => 'Dr. Sarah Mitchell',
                'name_ar' => 'د. سارة ميتشل',
                'specialties' => wp_json_encode(['Facials & Skincare', 'Dermal Fillers']),
                'bio' => 'An internationally trained dermatologist, Dr. Sarah excels in bespoke skincare regimens and medical-grade facials, helping patients achieve radiant, flawless complexions.',
                'bio_ar' => 'طبيبة أمراض جلدية مدربة دوليًا، تتفوق د. سارة في أنظمة العناية بالبشرة المخصصة وعلاجات الوجه الطبية، مما يساعد المرضى على تحقيق بشرة متألقة وخالية من العيوب.',
                'years_experience' => 6,
                'qualifications' => wp_json_encode(['MSc Dermatology, UK']),
                'crm_id' => null,
                'sync_status' => 'synced',
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ],
            [
                'branch_id' => $branch_ids[1],
                'name' => 'Dr. Fahad Al-Dosari',
                'name_ar' => 'د. فهد الدوسري',
                'specialties' => wp_json_encode(['Hair Transplant', 'Electrolysis/Hair Removal']),
                'bio' => 'Dr. Fahad is highly respected for his meticulous precision in Follicular Unit Extraction (FUE) hair transplants and permanent hair reduction strategies.',
                'bio_ar' => 'يحظى د. فهد باحترام كبير لدقته المتناهية في زراعة الشعر بتقنية الاقتطاف واستراتيجيات تقليل الشعر الدائمة.',
                'years_experience' => 15,
                'qualifications' => wp_json_encode(['ABHRS Diplomat', 'MD, King Abdulaziz University']),
                'crm_id' => null,
                'sync_status' => 'synced',
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ],
            [
                'branch_id' => $branch_ids[0],
                'name' => 'Dr. Maya Zaidan',
                'name_ar' => 'د. مايا زيدان',
                'specialties' => wp_json_encode(['Botox/Injectables', 'Skin Tightening']),
                'bio' => 'Known for her gentle touch and artistic eye, Dr. Maya combines injectable treatments with non-invasive lifting techniques to deliver striking yet balanced enhancements.',
                'bio_ar' => 'تعرف د. مايا بلمستها اللطيفة ونظرتها الفنية، حيث تجمع بين الحقن وتقنيات الشد غير الجراحية لتقديم تحسينات مذهلة ومتوازنة.',
                'years_experience' => 9,
                'qualifications' => wp_json_encode(['American Board of Anti-Aging & Regenerative Medicine']),
                'crm_id' => null,
                'sync_status' => 'synced',
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ]
        ];

        $doctor_ids = [];
        foreach ($doctors as $doctor) {
            $wpdb->insert("{$wpdb->prefix}ac_doctors", $doctor);
            $doctor_ids[] = $wpdb->insert_id;
        }

        // 3. Seed Slots (Next 14 Days)
        $start_date = new \DateTime();
        $end_date = clone $start_date;
        $end_date->modify('+14 days');

        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start_date, $interval, $end_date);

        $slots = [];
        foreach ($period as $date) {
            $day_of_week = (int)$date->format('w');
            
            // Friday Closed
            if ($day_of_week === 5) continue;

            // Saturday 10:00 AM–10:30 PM (22:30), Other days 10:00 AM–10:00 PM (22:00)
            $end_hour = ($day_of_week === 6) ? 22.5 : 22.0;

            foreach ($doctors as $i => $doctor_data) {
                $doctor_id = $doctor_ids[$i];
                $branch_id = $doctor_data['branch_id'];

                for ($hour = 10; $hour < $end_hour; $hour += 0.5) {
                    $h = floor($hour);
                    $m = ($hour - $h) * 60;
                    $time_string = sprintf('%02d:%02d:00', $h, $m);

                    $slots[] = [
                        'doctor_id' => $doctor_id,
                        'branch_id' => $branch_id,
                        'date' => $date->format('Y-m-d'),
                        'start_time' => $time_string,
                        'end_time' => date('H:i:00', strtotime("+30 minutes", strtotime($date->format('Y-m-d') . ' ' . $time_string))),
                        'status' => 'available',
                        'crm_id' => null,
                        'sync_status' => 'synced',
                        'created_at' => current_time('mysql'),
                        'updated_at' => current_time('mysql')
                    ];
                }
            }
        }

        // Batch insert slots (chunk to avoid huge query)
        $chunks = array_chunk($slots, 100);
        foreach ($chunks as $chunk) {
            $query = "INSERT INTO {$wpdb->prefix}ac_doctor_slots (doctor_id, branch_id, date, start_time, end_time, status, crm_id, sync_status, created_at, updated_at) VALUES ";
            $values = [];
            foreach ($chunk as $s) {
                $values[] = $wpdb->prepare(
                    "(%d, %d, %s, %s, %s, %s, NULL, %s, %s, %s)",
                    $s['doctor_id'], $s['branch_id'], $s['date'], $s['start_time'], $s['end_time'], $s['status'], $s['sync_status'], $s['created_at'], $s['updated_at']
                );
            }
            $query .= implode(', ', $values);
            $wpdb->query($query);
        }

        // 4. Seed Leads
        $leads = [
            [
                'name' => 'Amira Khalil',
                'mobile' => '+966511111111',
                'email' => 'amira.test@example.com',
                'service_interest' => 'Botox/Injectables',
                'campaign_source' => 'Instagram Ad - Summer Glow',
                'message' => 'Botox consultation inquiry. I have never done it before.',
                'status' => 'new',
                'created_at' => current_time('mysql')
            ],
            [
                'name' => 'Khaled Salman',
                'mobile' => '+966522222222',
                'email' => 'khaled.s@example.com',
                'service_interest' => 'Laser Resurfacing',
                'campaign_source' => 'Google Search',
                'message' => 'Laser resurfacing pricing for acne scars.',
                'status' => 'new',
                'created_at' => current_time('mysql')
            ],
            [
                'name' => 'Reem Youssef',
                'mobile' => '+966533333333',
                'email' => 'reem.y@example.com',
                'service_interest' => 'Dermal Fillers',
                'campaign_source' => 'Referral',
                'message' => 'Looking for lip filler pricing.',
                'status' => 'contacted',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ]
        ];

        foreach ($leads as $lead) {
            $wpdb->insert("{$wpdb->prefix}ac_leads", $lead);
        }
    }

    public static function clear(): void {
        global $wpdb;

        // Delete only rows where crm_id IS NULL
        $wpdb->query("DELETE FROM {$wpdb->prefix}ac_doctor_slots WHERE crm_id IS NULL");
        $wpdb->query("DELETE FROM {$wpdb->prefix}ac_appointments WHERE crm_id IS NULL");
        $wpdb->query("DELETE FROM {$wpdb->prefix}ac_doctors WHERE crm_id IS NULL");
        $wpdb->query("DELETE FROM {$wpdb->prefix}ac_branches WHERE crm_id IS NULL");
        $wpdb->query("DELETE FROM {$wpdb->prefix}ac_leads WHERE status IN ('new', 'contacted', 'converted')"); // Clear all local leads
    }
}
