<?php

namespace Drupal\bt_crm;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines access control handler for the Redhen organization type entity type.
 */
class RedhenOrgTypeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'add org entities');

      case 'delete':
        return parent::checkAccess($entity, $operation, $account);

      default:
        return parent::checkAccess($entity, $operation, $account);
    }
  }

}
