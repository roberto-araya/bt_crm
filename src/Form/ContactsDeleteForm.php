<?php

namespace Drupal\bt_crm\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting Contact entities.
 *
 * @ingroup redhen_contact
 */
class ContactsDeleteForm extends ContentEntityDeleteForm {

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->messenger = $container->get('messenger');
    return $instance;
  }

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
      $form_state->setRedirectUrl(new Url('bt_crm.persons'));
    }

    $this->messenger->addMessage($this->getDeletionMessage());
    $this->logDeletionMessage();
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.redhen_contact.canonical', ['redhen_contact' => $this->entity->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('¿Are you sure you want to eliminate the contact %title?', ['%title' => $this->entity->label()]);
  }

}
