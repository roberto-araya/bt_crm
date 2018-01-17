<?php

namespace Drupal\bt_crm_potential_client\Config;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Example configuration override.
 */
class ConfigCrmPotentialClientOverride implements ConfigFactoryOverrideInterface {

  private $btScheduleEmail;
  private $btSchedulePhoneCall;
  private $btScheduleMeeting;
  private $btOpportunity;
  private $btScheduleVisit;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->btScheduleEmail = $configFactory->get('field.field.bt_activities.bt_schedule_email.field_bt_contact');
    $this->btSchedulePhoneCall = $configFactory->get('field.field.bt_activities.bt_schedule_phone_call.field_bt_contact');
    $this->btScheduleMeeting = $configFactory->get('field.field.bt_activities.bt_schedule_meeting.field_bt_participants');
    $this->btOpportunity = $configFactory->get('field.field.bt_opportunities.bt_opportunity.field_bt_contact');
    $this->btScheduleVisit = $configFactory->get('field.field.bt_activities.bt_schedule_visit.field_bt_contact');

  }

  /**
   * {@inheritdoc}
   */
  public function loadOverrides($names) {
    $overrides = array();

    $client_values = [
      'bt_potential_client' => 'bt_potential_client',
    ];
    // field.field.bt_activities.bt_schedule_email.field_bt_contact.
    if (in_array('field.field.bt_activities.bt_schedule_email.field_bt_contact', $names)) {
      $field = $this->btScheduleEmail;
      $filter_values = $field->get('settings.handler_settings.target_bundles');
      $values = array_merge($filter_values, $client_values);
      $overrides['field.field.bt_activities.bt_schedule_email.field_bt_contact']['settings']['handler_settings']['target_bundles'] = $values;
    }

    // field.field.bt_activities.bt_schedule_phone_call.field_bt_contact.
    if (in_array('field.field.bt_activities.bt_schedule_phone_call.field_bt_contact', $names)) {
      $field = $this->btSchedulePhoneCall;
      $filter_values = $field->get('settings.handler_settings.target_bundles');
      $values = array_merge($filter_values, $client_values);
      $overrides['field.field.bt_activities.bt_schedule_phone_call.field_bt_contact']['settings']['handler_settings']['target_bundles'] = $values;
    }

    // field.field.bt_activities.bt_schedule_meeting.field_bt_participants.
    if (in_array('field.field.bt_activities.bt_schedule_meeting.field_bt_participants', $names)) {
      $field = $this->btScheduleMeeting;
      $filter_values = $field->get('settings.handler_settings.target_bundles');
      $values = array_merge($filter_values, $client_values);
      $overrides['field.field.bt_activities.bt_schedule_meeting.field_bt_participants']['settings']['handler_settings']['target_bundles'] = $values;
    }

    // field.field.bt_opportunities.bt_opportunity.field_bt_contact.
    if (in_array('field.field.bt_opportunities.bt_opportunity.field_bt_contact', $names)) {
      $field = $this->btOpportunity;
      $filter_values = $field->get('settings.handler_settings.target_bundles');
      $values = array_merge($filter_values, $client_values);
      $overrides['field.field.bt_opportunities.bt_opportunity.field_bt_contact']['settings']['handler_settings']['target_bundles'] = $values;
    }

    // field.field.bt_activities.bt_schedule_visit.field_bt_contact.
    if (in_array('field.field.bt_activities.bt_schedule_visit.field_bt_contact', $names)) {
      $field = $this->btScheduleVisit;
      $filter_values = $field->get('settings.handler_settings.target_bundles');
      $values = array_merge($filter_values, $client_values);
      $overrides['field.field.bt_activities.bt_schedule_visit.field_bt_contact']['settings']['handler_settings']['target_bundles'] = $values;
    }

    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'ConfigCrmPotentialClientOverride';
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
