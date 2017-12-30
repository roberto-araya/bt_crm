<?php

namespace Drupal\bt_crm;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the bt_opportunities entity type.
 *
 */
class OpportunitiesAccessControlHandler extends EntityAccessControlHandler {

  //protected $viewLabelOperation = TRUE;

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'create bt_opportunities entities');
      default:
        return parent::checkAccess($entity, $operation, $account);
    }
  }

}