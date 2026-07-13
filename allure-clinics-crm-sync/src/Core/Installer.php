<?php

namespace AllureClinics\Core;

class Installer {

    /**
     * Creates custom tables required by the plugin.
     */
    public static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        
        // Include the dbDelta function
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = "
        CREATE TABLE {$wpdb->prefix}ac_branches (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            crm_id varchar(100) DEFAULT NULL,
            name varchar(255) NOT NULL,
            name_ar varchar(255) DEFAULT NULL,
            address text DEFAULT NULL,
            geo_lat varchar(50) DEFAULT NULL,
            geo_lng varchar(50) DEFAULT NULL,
            phone varchar(50) DEFAULT NULL,
            sync_status enum('pending_push', 'synced', 'sync_failed') DEFAULT 'synced',
            last_synced_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY crm_id (crm_id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}ac_doctors (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            crm_id varchar(100) DEFAULT NULL,
            branch_id bigint(20) unsigned DEFAULT NULL,
            name varchar(255) NOT NULL,
            name_ar varchar(255) DEFAULT NULL,
            bio text DEFAULT NULL,
            bio_ar text DEFAULT NULL,
            photo_attachment_id bigint(20) unsigned DEFAULT NULL,
            qualifications json DEFAULT NULL,
            specialties json DEFAULT NULL,
            years_experience int(11) DEFAULT NULL,
            sync_status enum('pending_push', 'synced', 'sync_failed') DEFAULT 'synced',
            last_synced_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY crm_id (crm_id),
            KEY branch_id (branch_id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}ac_doctor_slots (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            doctor_id bigint(20) unsigned NOT NULL,
            branch_id bigint(20) unsigned NOT NULL,
            date date NOT NULL,
            start_time time NOT NULL,
            end_time time NOT NULL,
            status enum('available', 'booked', 'blocked', 'leave') NOT NULL DEFAULT 'available',
            crm_id varchar(100) DEFAULT NULL,
            sync_status enum('pending_push', 'synced', 'sync_failed') DEFAULT 'synced',
            last_synced_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY doctor_id (doctor_id),
            KEY branch_id (branch_id),
            KEY date (date),
            KEY status (status),
            KEY crm_id (crm_id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}ac_patients (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            crm_id varchar(100) DEFAULT NULL,
            mobile_number varchar(50) NOT NULL,
            name varchar(255) DEFAULT NULL,
            email varchar(255) DEFAULT NULL,
            dob date DEFAULT NULL,
            otp_verified_at datetime DEFAULT NULL,
            sync_status enum('pending_push', 'synced', 'sync_failed') DEFAULT 'synced',
            last_synced_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY mobile_number (mobile_number),
            KEY crm_id (crm_id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}ac_appointments (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            crm_id varchar(100) DEFAULT NULL,
            patient_id bigint(20) unsigned NOT NULL,
            doctor_id bigint(20) unsigned NOT NULL,
            branch_id bigint(20) unsigned NOT NULL,
            slot_id bigint(20) unsigned NOT NULL,
            status enum('pending', 'confirmed', 'cancelled', 'completed', 'rescheduled') DEFAULT 'pending',
            consultation_fee decimal(10,2) DEFAULT NULL,
            payment_status varchar(50) DEFAULT NULL,
            source enum('website', 'mobile_app') DEFAULT 'website',
            sync_status enum('pending_push', 'synced', 'sync_failed') DEFAULT 'pending_push',
            last_synced_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY patient_id (patient_id),
            KEY doctor_id (doctor_id),
            KEY crm_id (crm_id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}ac_leads (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            mobile varchar(50) NOT NULL,
            email varchar(255) DEFAULT NULL,
            service_interest varchar(255) DEFAULT NULL,
            campaign_source varchar(255) DEFAULT NULL,
            message text DEFAULT NULL,
            status varchar(50) DEFAULT 'new',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}ac_otp_requests (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            mobile_number varchar(50) NOT NULL,
            otp_hash varchar(255) NOT NULL,
            expires_at datetime NOT NULL,
            attempts int(11) DEFAULT 0,
            verified_at datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY mobile_number (mobile_number)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}ac_sync_log (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            entity_type varchar(50) NOT NULL,
            entity_id varchar(100) DEFAULT NULL,
            direction enum('pull', 'push') NOT NULL,
            status varchar(50) NOT NULL,
            payload_snapshot longtext DEFAULT NULL,
            error_message text DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY entity_type (entity_type)
        ) $charset_collate;
        ";

        dbDelta( $sql );

        update_option( 'allure_clinics_db_version', ALLURE_CLINICS_VERSION );
    }
}
