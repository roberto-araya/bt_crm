<?php
/**
 *
 */

namespace Drupal\bt_crm_supplier\Config;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Example configuration override.
 */
class ConfigCrmSupplierOverride implements ConfigFactoryOverrideInterface {

    private $field_bt_organization;

    public function __construct(ConfigFactory $ConfigFactory)
    {
        $this->field_bt_organization = $ConfigFactory->get('field.field.bt_opportunities.bt_opportunity.field_bt_organization');

    }

    public function loadOverrides($names) {
        $overrides = array();

        $client_values = [
            'bt_supplier' => 'bt_supplier'
        ];
        // field.field.bt_opportunities.bt_opportunity.field_bt_organization
        if (in_array('field.field.bt_opportunities.bt_opportunity.field_bt_organization', $names)) {
            $field = $this->field_bt_organization;
            $filter_values = $field->get('settings.handler_settings.target_bundles');
            $values = array_merge($filter_values,$client_values);
            $overrides['field.field.bt_opportunities.bt_opportunity.field_bt_organization']['settings']['handler_settings']['target_bundles'] = $values;
        }
        return $overrides;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheSuffix() {
        return 'ConfigCrmSupplierOverride';
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheableMetadata($name) {
        return new CacheableMetadata();
    }

    /**
     * {@inheritdoc}
     */
    public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
        return NULL;
    }

}