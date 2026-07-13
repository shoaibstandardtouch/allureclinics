<?php

namespace AllureClinics\Admin;

use AllureClinics\Repositories\DoctorRepository;
use AllureClinics\Repositories\BranchRepository;

class DoctorsPage {

    private DoctorRepository $doctorRepo;
    private BranchRepository $branchRepo;

    public function __construct(DoctorRepository $doctorRepo, BranchRepository $branchRepo) {
        $this->doctorRepo = $doctorRepo;
        $this->branchRepo = $branchRepo;
    }

    public function render(): void {
        if (isset($_POST['allure_clinics_doctor_nonce']) && wp_verify_nonce($_POST['allure_clinics_doctor_nonce'], 'save_doctor')) {
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $this->doctorRepo->delete(absint($_POST['doctor_id']));
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Doctor deleted.', 'allure-clinics') . '</p></div>';
            } else {
                $specialties = array_map('trim', explode(',', $_POST['specialties']));
                $qualifications = array_map('trim', explode(',', $_POST['qualifications']));

                $data = [
                    'branch_id' => absint($_POST['branch_id']),
                    'name' => sanitize_text_field($_POST['name']),
                    'name_ar' => sanitize_text_field($_POST['name_ar']),
                    'bio' => sanitize_textarea_field($_POST['bio']),
                    'bio_ar' => sanitize_textarea_field($_POST['bio_ar']),
                    'specialties' => wp_json_encode($specialties),
                    'qualifications' => wp_json_encode($qualifications),
                    'years_experience' => absint($_POST['years_experience'])
                ];

                if (!empty($_POST['doctor_id'])) {
                    $this->doctorRepo->update(absint($_POST['doctor_id']), $data);
                    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Doctor updated.', 'allure-clinics') . '</p></div>';
                } else {
                    $this->doctorRepo->create($data);
                    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Doctor added.', 'allure-clinics') . '</p></div>';
                }
            }
        }

        $doctors = $this->doctorRepo->getAll();
        $branches = $this->branchRepo->getAll();

        include plugin_dir_path(__FILE__) . 'views/doctors.php';
    }
}
