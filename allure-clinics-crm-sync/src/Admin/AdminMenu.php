<?php

namespace AllureClinics\Admin;

class AdminMenu {

    private Dashboard $dashboard;
    private SettingsPage $settingsPage;
    private GetStartedPage $getStartedPage;

    public function __construct(Dashboard $dashboard, SettingsPage $settingsPage, GetStartedPage $getStartedPage) {
        $this->dashboard = $dashboard;
        $this->settingsPage = $settingsPage;
        $this->getStartedPage = $getStartedPage;
        
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
