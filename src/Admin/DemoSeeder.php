<?php

namespace AllureClinics\Admin;

class DemoSeeder {

    public static function seed(): void {
        global $wpdb;

        // Idempotency: clear existing demo data before seeding
        self::clear();

        // 1. Seed Locations (Branches) in Bookly if addon is active
        $location_ids = [];
        if (class_exists('\BooklyLocations\Lib\Entities\Location')) {
            $branches = [
                [
                    'name' => 'Allure Clinics — North Branch',
                    'info' => "عيادات ألور — فرع الشمال\nAl Malqa District, Riyadh 13521\n+966500000001"
                ],
                [
                    'name' => 'Allure Clinics — Olaya Branch',
                    'info' => "عيادات ألور — فرع العليا\nOlaya Street, Riyadh 12211\n+966500000002"
                ]
            ];

            foreach ($branches as $b) {
                $loc = new \BooklyLocations\Lib\Entities\Location();
                $loc->setName($b['name']);
                $loc->setInfo($b['info']);
                $loc->save();
                $location_ids[] = $loc->getId();
            }
        }

        // 2. Seed Staff (Doctors) in Bookly
        $staff_ids = [];
        if (class_exists('\Bookly\Lib\Entities\Staff')) {
            $doctors = [
                [
                    'full_name' => 'Dr. Layan Al-Fayez',
                    'email' => 'layan@example.com',
                    'phone' => '+966500000101',
                    'info' => "د. ليان الفايز\nBotox/Injectables, Dermal Fillers\nDr. Layan is a leading aesthetic physician specializing in advanced non-surgical facial rejuvenation. With over 8 years of experience, she is renowned for her natural-looking results."
                ],
                [
                    'full_name' => 'Dr. Omar Tariq',
                    'email' => 'omar@example.com',
                    'phone' => '+966500000102',
                    'info' => "د. عمر طارق\nLaser Resurfacing, Skin Tightening\nDr. Omar brings unparalleled expertise in cutting-edge laser therapies and radiofrequency skin tightening."
                ],
                [
                    'full_name' => 'Dr. Sarah Mitchell',
                    'email' => 'sarah@example.com',
                    'phone' => '+966500000103',
                    'info' => "د. سارة ميتشل\nFacials & Skincare, Dermal Fillers\nAn internationally trained dermatologist, Dr. Sarah excels in bespoke skincare regimens and medical-grade facials."
                ],
                [
                    'full_name' => 'Dr. Fahad Al-Dosari',
                    'email' => 'fahad@example.com',
                    'phone' => '+966500000104',
                    'info' => "د. فهد الدوسري\nHair Transplant, Electrolysis/Hair Removal\nDr. Fahad is highly respected for his meticulous precision in Follicular Unit Extraction (FUE) hair transplants."
                ],
                [
                    'full_name' => 'Dr. Maya Zaidan',
                    'email' => 'maya@example.com',
                    'phone' => '+966500000105',
                    'info' => "د. مايا زيدان\nBotox/Injectables, Skin Tightening\nKnown for her gentle touch and artistic eye, Dr. Maya combines injectable treatments with non-invasive lifting techniques."
                ]
            ];

            foreach ($doctors as $d) {
                $staff = new \Bookly\Lib\Entities\Staff();
                $staff->setFullName($d['full_name']);
                $staff->setEmail($d['email']);
                $staff->setPhone($d['phone']);
                $staff->setInfo($d['info']);
                $staff->save();
                $staff_ids[] = $staff->getId();

                // If locations addon is active, associate staff with a location randomly
                if (!empty($location_ids) && class_exists('\BooklyLocations\Lib\Entities\StaffLocation')) {
                    $staffLocation = new \BooklyLocations\Lib\Entities\StaffLocation();
                    $staffLocation->setStaffId($staff->getId());
                    $staffLocation->setLocationId($location_ids[array_rand($location_ids)]);
                    $staffLocation->save();
                }
            }
        }

        // 3. Seed Leads (with is_demo = 1)
        $leads = [
            [
                'name' => 'Amira Khalil',
                'mobile' => '+966511111111',
                'email' => 'amira.test@example.com',
                'service_interest' => 'Botox/Injectables',
                'campaign_source' => 'Instagram Ad - Summer Glow',
                'message' => 'Botox consultation inquiry. I have never done it before.',
                'status' => 'new',
                'is_demo' => 1,
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
                'is_demo' => 1,
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
                'is_demo' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ]
        ];

        foreach ($leads as $lead) {
            $wpdb->insert("{$wpdb->prefix}ac_leads", $lead);
        }

        // Set flag indicating demo data is loaded
        update_option('ac_demo_data_loaded', 1);
    }

    public static function clear(): void {
        global $wpdb;

        // 1. Delete Bookly Locations created by demo seeder (identifiable by exact names)
        if (class_exists('\BooklyLocations\Lib\Entities\Location')) {
            $locationNames = ['Allure Clinics — North Branch', 'Allure Clinics — Olaya Branch'];
            foreach ($locationNames as $name) {
                $locations = \BooklyLocations\Lib\Entities\Location::query()->where('name', $name)->find();
                foreach ($locations as $loc) {
                    $loc->delete();
                }
            }
        }

        // 2. Delete Bookly Staff created by demo seeder
        if (class_exists('\Bookly\Lib\Entities\Staff')) {
            $staffEmails = ['layan@example.com', 'omar@example.com', 'sarah@example.com', 'fahad@example.com', 'maya@example.com'];
            foreach ($staffEmails as $email) {
                $staffList = \Bookly\Lib\Entities\Staff::query()->where('email', $email)->find();
                foreach ($staffList as $staff) {
                    $staff->delete();
                }
            }
        }

        // 3. Delete only Demo Leads safely
        $wpdb->query("DELETE FROM {$wpdb->prefix}ac_leads WHERE is_demo = 1");

        // Clear flag
        delete_option('ac_demo_data_loaded');
    }
}
