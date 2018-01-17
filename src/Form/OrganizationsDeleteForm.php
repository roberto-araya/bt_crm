<?php

namespace Drupal\bt_crm\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting Contact entities.
 *
 * @ingroup redhen_contact
 */
class OrganizationsDeleteForm extends ContentEntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
    $entity = $this->getEntity();

    // Make sure that deleting a translation does not delete the whole entity.
    if (!$entity->isDefaultTranslation()) {
      $untranslated_entity = $entity->getUntranslated();
      $untranslated_entity->removeTranslation($entity->language()->getId());
      $untranslated_entity->save();
      $form_state->setRedirectUrl($untranslated_entity->urlInfo('canonical'));
    }
    else {
      $entity->delete();
      $form_state->setRedirectUrl(new Url('page_manager.page_view_app_organizations_app_organizations-panels_variant-0'));
    }

    drupal_set_message($this->getDeletionMessage());
    $this->logDeletionMessage();
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.redhen_org.canonical', ['redhen_org' => $this->entity->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('¿Are you sure you want to eliminate the organization %title?', array('%title' => $this->entity->label()));
  }

}
