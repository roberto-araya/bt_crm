<?php

namespace Drupal\bt_crm;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the opportunity entity type.
 */
class BtOpportunityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view opportunity');

      case 'update':
        return AccessResult::allowedIfHasPermissions($account, ['edit opportunity', 'administer opportunity'], 'OR');

      case 'delete':
        return AccessResult::allowedIfHasPermissions($account, ['delete opportunity', 'administer opportunity'], 'OR');

      default:
        // No opinion.
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create opportunity', 'administer opportunity'], 'OR');
  }

}
