<?php

namespace AllureClinics\Admin;

use AllureClinics\Repositories\BranchRepository;

class BranchesPage {

    private BranchRepository $branchRepo;

    public function __construct(BranchRepository $branchRepo) {
        $this->branchRepo = $branchRepo;
    }

    public function render(): void {
        if (isset($_POST['allure_clinics_branch_nonce']) && wp_verify_nonce($_POST['allure_clinics_branch_nonce'], 'save_branch')) {
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $this->branchRepo->delete(absint($_POST['branch_id']));
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Branch deleted.', 'allure-clinics') . '</p></div>';
            } else {
                $data = [
                    'name' => sanitize_text_field($_POST['name']),
                    'name_ar' => sanitize_text_field($_POST['name_ar']),
                    'address' => sanitize_text_field($_POST['address']),
                    'phone' => sanitize_text_field($_POST['phone']),
                ];

                if (!empty($_POST['branch_id'])) {
                    $this->branchRepo->update(absint($_POST['branch_id']), $data);
                    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Branch updated.', 'allure-clinics') . '</p></div>';
                } else {
                    $this->branchRepo->create($data);
                    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Branch added.', 'allure-clinics') . '</p></div>';
                }
            }
        }

        $branches = $this->branchRepo->getAll();

        include plugin_dir_path(__FILE__) . 'views/branches.php';
    }
}
