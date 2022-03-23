<?php

namespace Drupal\bt_crm\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form submission handler.
 *
 * @WebformHandler(
 *   id = "create_redhen_contact",
 *   label = @Translation("Create a Redhen Contact"),
 *   category = @Translation("Btester"),
 *   description = @Translation("Create a Redhen contact if the email don't exist in the contact database."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class CreateRedhenContact extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory'),
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('webform_submission.conditions_validator'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
    $data = $webform_submission->getData();
    $query = $this->entityTypeManager->getStorage('redhen_contact')->getQuery();

    // We need to know if the person sending the form exist in the CRM.
    $query->condition('email', $data['email']);
    $entity_ids = $query->execute();

    // If don't exist then we create it.
    if (empty($entity_ids)) {
      // Create a new redhen_contact of type bt_potential_client.
      $prospect = Contact::create(['type' => 'bt_potential_client']);
      // Set fields.
      $prospect->setEmail($data['email']);
      $prospect->set('first_name', $data['name']);
      $prospect->save();
    }
  }

}
