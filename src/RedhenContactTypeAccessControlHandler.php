<?php

namespace Drupal\bt_crm;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the Redhen contact type entity type.
 */
class RedhenContactTypeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'add contact entities');

      case 'delete':
        return parent::checkAccess($entity, $operation, $account);

      default:
        return parent::checkAccess($entity, $operation, $account);

    }
  }

}
