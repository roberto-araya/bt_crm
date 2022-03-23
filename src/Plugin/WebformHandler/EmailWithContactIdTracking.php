<?php

namespace Drupal\bt_crm\Plugin\WebformHandler;

use Drupal\webform\Plugin\WebformHandler\EmailWebformHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\redhen_contact\Entity\Contact;
use Drupal\field_collection\Entity\FieldCollectionItem;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Emails a webform submission.
 *
 * @WebformHandler(
 *   id = "email_contact_id_tracking",
 *   label = @Translation("Email with contact id for tracking"),
 *   category = @Translation("Btester"),
 *   description = @Translation("Create a Redhen contact of type potential client and sends a email."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class EmailWithContactIdTracking extends EmailWebformHandler {

  /**
   * Prospect Contact ID.
   *
   * @var idContacto
   */
  protected $idContacto;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')->get('webform'),
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('config.factory'),
      $container->get('plugin.manager.mail'),
      $container->get('webform.token_manager'),
      $container->get('plugin.manager.webform.element'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
    $query = $this->entityTypeManager->getStorage('redhen_contact')->getQuery();

    // We need to know if the person sending the form exist in the CRM.
    $query->condition('email', $webform_submission->getData('email'));
    $entity_ids = $query->execute();

    // If don't exist then we create it.
    if (empty($entity_ids)) {
      // Create a new redhen_contact of type bt_potential_client.
      $prospect = Contact::create(['type' => 'bt_potential_client']);
      // Set fields.
      $prospect->setEmail($webform_submission->getData('email'));
      $prospect->set('first_name', $webform_submission->getData('name'));
      $prospect->save();
      // Set phone field collection.
      $fc = FieldCollectionItem::create(['field_name' => 'field_bt_phones']);
      $fc->field_bt_phone_type->setValue('movil');
      $fc->field_bt_phone->setValue($webform_submission->getData('phone'));
      $fc->setHostEntity($prospect);
      $fc->save();
      // Link field collection with prospect client object
      // and save prospect in the CRM.
      $prospect->field_bt_phones[] = ['field_collection_item' => $fc];
      $prospect->save();

      $this->idContacto = $prospect->id();
    }
    else {
      $this->idContacto = reset($entity_ids);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(WebformSubmissionInterface $webform_submission) {
    $webform_submission->setData([
      'name' => $webform_submission->getData('name'),
      'email' => $webform_submission->getData('email'),
      'phone' => $webform_submission->getData('phone'),
      'id_contacto' => $this->idContacto,
    ]);
  }

}
