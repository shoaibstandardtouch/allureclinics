<?php

namespace AllureClinics\Repositories;

class BranchRepository {

    public function getAll(): array {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ac_branches ORDER BY name ASC", ARRAY_A);
    }

    public function getById(int $id): ?array {
        global $wpdb;
        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ac_branches WHERE id = %d", $id), ARRAY_A);
        return $result ?: null;
    }

    public function create(array $data): int {
        global $wpdb;
        $data['created_at'] = current_time('mysql');
        $data['updated_at'] = current_time('mysql');
        $data['crm_id'] = null;
        $data['sync_status'] = 'synced'; // Local branches don't sync until CRM is connected

        $wpdb->insert("{$wpdb->prefix}ac_branches", $data);
        return $wpdb->insert_id;
    }

    public function update(int $id, array $data): bool {
        global $wpdb;
        $data['updated_at'] = current_time('mysql');
        $updated = $wpdb->update("{$wpdb->prefix}ac_branches", $data, ['id' => $id]);
        return $updated !== false;
    }

    public function delete(int $id): bool {
        global $wpdb;
        $deleted = $wpdb->delete("{$wpdb->prefix}ac_branches", ['id' => $id]);
        return $deleted !== false;
    }
}
