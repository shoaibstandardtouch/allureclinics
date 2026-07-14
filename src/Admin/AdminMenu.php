<?php

namespace AllureClinics\Admin;

class AdminMenu {

    private Dashboard $dashboard;
    private SettingsPage $settingsPage;
    private GetStartedPage $getStartedPage;
    private LeadsPage $leadsPage;
    private BranchesPage $branchesPage;
    private ?BranchesPage $branchesPage;
    private ?DoctorsPage $doctorsPage;

    public function __construct(
        Dashboard $dashboard, 
        SettingsPage $settingsPage, 
        GetStartedPage $getStartedPage, 
        LeadsPage $leadsPage,
        ?BranchesPage $branchesPage = null,
        ?DoctorsPage $doctorsPage = null
    ) {
        $this->dashboard = $dashboard;
        $this->settingsPage = $settingsPage;
        $this->getStartedPage = $getStartedPage;
        $this->leadsPage = $leadsPage;
        $this->branchesPage = $branchesPage;
        $this->doctorsPage = $doctorsPage;
        
        add_action('admin_menu', [$this, 'registerMenus']);
    }

    public function registerMenus(): void {
        add_menu_page(
            __('Allure CRM Sync', 'allure-clinics'),
            __('Allure CRM', 'allure-clinics'),
            'manage_options',
            'allure-crm-dashboard',
            [$this->dashboard, 'render'],
            'dashicons-sync',
            30
        );

        add_submenu_page(
            'allure-crm-dashboard',
            __('Dashboard', 'allure-clinics'),
            __('Dashboard', 'allure-clinics'),
            'manage_options',
            'allure-crm-dashboard',
            [$this->dashboard, 'render']
        );

        if ($this->branchesPage) {
            add_submenu_page(
                'allure-crm-dashboard',
                __('Branches', 'allure-clinics'),
                __('Branches', 'allure-clinics'),
                'manage_options',
                'allure-crm-branches',
                [$this->branchesPage, 'render']
            );
        }

        if ($this->doctorsPage) {
            add_submenu_page(
                'allure-crm-dashboard',
                __('Doctors', 'allure-clinics'),
                __('Doctors', 'allure-clinics'),
                'manage_options',
                'allure-crm-doctors',
                [$this->doctorsPage, 'render']
            );
        }

        add_submenu_page(
            'allure-crm-dashboard',
            __('Leads', 'allure-clinics'),
            __('Leads', 'allure-clinics'),
            'manage_options',
            'allure-crm-leads',
            [$this->leadsPage, 'render']
        );

        add_submenu_page(
            'allure-crm-dashboard',
            __('Settings', 'allure-clinics'),
            __('Settings', 'allure-clinics'),
            'manage_options',
            'allure-crm-settings',
            [$this->settingsPage, 'render']
        );

        add_submenu_page(
            'allure-crm-dashboard',
            __('Getting Started', 'allure-clinics'),
            __('Getting Started', 'allure-clinics'),
            'manage_options',
            'allure-crm-get-started',
            [$this->getStartedPage, 'render']
        );
    }
}
