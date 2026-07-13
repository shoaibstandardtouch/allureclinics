<?php

namespace AllureClinics\CRM;

use AllureClinics\CRM\Adapters\NullAdapter;

class AdapterFactory {

    /**
     * Get the registered adapters.
     * 
     * @return array<string, string> Array of slug => ClassName
     */
    public static function getAvailableAdapters(): array {
        $default_adapters = [
            'null' => NullAdapter::class
        ];

        /**
         * Filter to register additional CRM adapters.
         * 
         * @param array $adapters Existing adapters ['slug' => 'ClassName']
         */
        return apply_filters('allure_clinics_available_adapters', $default_adapters);
    }

    /**
     * Get an instance of the configured CRM Adapter.
     */
    public function getActiveAdapter(): CrmAdapterInterface {
        $adapters = self::getAvailableAdapters();
        
        $active_slug = get_option('ac_crm_adapter', 'null');

        if (array_key_exists($active_slug, $adapters) && class_exists($adapters[$active_slug])) {
            $class = $adapters[$active_slug];
            $adapter = new $class();
            if ($adapter instanceof CrmAdapterInterface) {
                return $adapter;
            }
        }

        // Fallback to NullAdapter if configured adapter is missing/invalid
        return new NullAdapter();
    }
}
