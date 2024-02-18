<?php

namespace Drupal\bt_crm;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining an opportunity entity type.
 */
interface BtOpportunityInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the opportunity title.
   *
   * @return string
   *   Title of the opportunity.
   */
  public function getTitle();

  /**
   * Sets the opportunity title.
   *
   * @param string $title
   *   The opportunity title.
   *
   * @return \Drupal\bt_crm\BtOpportunityInterface
   *   The called opportunity entity.
   */
  public function setTitle($title);

  /**
   * Gets the opportunity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the opportunity.
   */
  public function getCreatedTime();

  /**
   * Sets the opportunity creation timestamp.
   *
   * @param int $timestamp
   *   The opportunity creation timestamp.
   *
   * @return \Drupal\bt_crm\BtOpportunityInterface
   *   The called opportunity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the opportunity status.
   *
   * @return bool
   *   TRUE if the opportunity is enabled, FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Sets the opportunity status.
   *
   * @param bool $status
   *   TRUE to enable this opportunity, FALSE to disable.
   *
   * @return \Drupal\bt_crm\BtOpportunityInterface
   *   The called opportunity entity.
   */
  public function setStatus($status);

}
