<?php

/**
 * @file
 * Contains theme implementations.
 */

use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;

/**
 * Preprocess the entity bundles for listing.
 */
function template_preprocess_activities_add_list(&$variables) {
  $variables['types'] = array();
  if (!empty($variables['content'])) {
    foreach ($variables['content'] as $type) {
      $options['query'] = array();
      $variables['types'][$type->type] = array(
        'type' => $type->name,
        'description' => Xss::filterAdmin($type->description),
      );
      if (!empty($variables['entity_info'])) {
        $entity_info = $variables['entity_info'];
        if ($type->type == 'bt_schedule_meeting') {

          $options['query']['edit[field_bt_participants]'] = $entity_info['contact_label'] . ' (' . $entity_info['contact_id'] . ')';
        }
        else {
          $options['query']['edit[field_bt_contact]'] = $entity_info['contact_label'] . ' (' . $entity_info['contact_id'] . ')';
        }
        if (array_key_exists('opportunity_id', $entity_info)) {
          $options['query']['edit[field_bt_opportunity]'] = $entity_info['opportunity_label'] . ' (' . $entity_info['opportunity_id'] . ')';
        }
        $variables['types'][$type->type]['add_link'] = new Url('bt_activities.add', array(
          'eck_entity_type' => $type->getEntityType()->getBundleOf(),
          'eck_entity_bundle' => $type->type,
        ),
          $options);
      }
      else {
        $variables['types'][$type->type]['add_link'] = new Url('bt_activities.add', array(
          'eck_entity_type' => $type->getEntityType()->getBundleOf(),
          'eck_entity_bundle' => $type->type,
        ));
      }
    }
  }

}
