<?php
/**
 *
 */

namespace Drupal\bt_crm_client\Config;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Example configuration override.
 */
class ConfigCrmClientOverride implements ConfigFactoryOverrideInterface {

    private $bt_schedule_email;
    private $bt_schedule_phone_call;
    private $bt_schedule_meeting;
    private $bt_opportunity;
    private $bt_schedule_visit;

    public function __construct(ConfigFactory $ConfigFactory)
    {
        $this->bt_schedule_email = $ConfigFactory->get('field.field.bt_activities.bt_schedule_email.field_bt_contact');
        $this->bt_schedule_phone_call = $ConfigFactory->get('field.field.bt_activities.bt_schedule_phone_call.field_bt_contact');
        $this->bt_schedule_meeting = $ConfigFactory->get('field.field.bt_activities.bt_schedule_meeting.field_bt_participants');
        $this->bt_opportunity = $ConfigFactory->get('field.field.bt_opportunities.bt_opportunity.field_bt_contact');
        $this->bt_schedule_visit = $ConfigFactory->get('field.field.bt_activities.bt_schedule_visit.field_bt_contact');

    }

    public function loadOverrides($names) {
        $overrides = array();

        $client_values = [
            'bt_client' => 'bt_client'
        ];
        // field.field.bt_activities.bt_schedule_email.field_bt_contact
        if (in_array('field.field.bt_activities.bt_schedule_email.field_bt_contact', $names)) {
            $field = $this->bt_schedule_email;
            $filter_values = $field->get('settings.handler_settings.target_bundles');
            $values = array_merge($filter_values,$client_values);
            $overrides['field.field.bt_activities.bt_schedule_email.field_bt_contact']['settings']['handler_settings']['target_bundles'] = $values;
        }

        // field.field.bt_activities.bt_schedule_phone_call.field_bt_contact
        if (in_array('field.field.bt_activities.bt_schedule_phone_call.field_bt_contact', $names)) {
            $field = $this->bt_schedule_phone_call;
            $filter_values = $field->get('settings.handler_settings.target_bundles');
            $values = array_merge($filter_values,$client_values);
            $overrides['field.field.bt_activities.bt_schedule_phone_call.field_bt_contact']['settings']['handler_settings']['target_bundles'] = $values;
        }

        // field.field.bt_activities.bt_schedule_meeting.field_bt_participants
        if (in_array('field.field.bt_activities.bt_schedule_meeting.field_bt_participants', $names)) {
            $field = $this->bt_schedule_meeting;
            $filter_values = $field->get('settings.handler_settings.target_bundles');
            $values = array_merge($filter_values,$client_values);
            $overrides['field.field.bt_activities.bt_schedule_meeting.field_bt_participants']['settings']['handler_settings']['target_bundles'] = $values;
        }

        // field.field.bt_opportunities.bt_opportunity.field_bt_contact
        if (in_array('field.field.bt_opportunities.bt_opportunity.field_bt_contact', $names)) {
            $field = $this->bt_opportunity;
            $filter_values = $field->get('settings.handler_settings.target_bundles');
            $values = array_merge($filter_values,$client_values);
            $overrides['field.field.bt_opportunities.bt_opportunity.field_bt_contact']['settings']['handler_settings']['target_bundles'] = $values;
        }

        // field.field.bt_activities.bt_schedule_visit.field_bt_contact
        if (in_array('field.field.bt_activities.bt_schedule_visit.field_bt_contact', $names)) {
            $field = $this->bt_schedule_visit;
            $filter_values = $field->get('settings.handler_settings.target_bundles');
            $values = array_merge($filter_values,$client_values);
            $overrides['field.field.bt_activities.bt_schedule_visit.field_bt_contact']['settings']['handler_settings']['target_bundles'] = $values;
        }
        return $overrides;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheSuffix() {
        return 'ConfigCrmClientOverride';
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