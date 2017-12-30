<?php

namespace Drupal\bt_crm\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\WebformHandlerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\redhen_contact\Entity\Contact;
use \Drupal\field_collection\Entity\FieldCollectionItem;
use Drupal\webform\WebformSubmissionInterface;


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
     * EntityQuery for Redhen contacts.
     *
     * @var Drupal\Core\Entity\Query\QueryFactory
     */
    protected $entityQuery;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerInterface $logger, EntityTypeManagerInterface $entity_type_manager, QueryFactory $entityQuery) {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $logger, $entity_type_manager);

        $this->entityQuery = $entityQuery;
    }

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
            $container->get('entity.query')
        );
    }


    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
        $query = $this->entityQuery->get('redhen_contact');
        // We need to know if the person sending the form exist in the CRM.
        $query->condition('email', $webform_submission->getData('email'));
        $entity_ids = $query->execute();

        // If don't exist then we create it.
        if (empty($entity_ids)) {
            // Create a new redhen_contact of type bt_potential_client
            $prospect = Contact::create(['type' => 'bt_potential_client']);
            // Set fields
            $prospect->setEmail($webform_submission->getData('email'));
            $prospect->set('first_name', $webform_submission->getData('name'));
            $prospect->save();
            // set phone field collection
            $fc = FieldCollectionItem::create(['field_name' => 'field_bt_phones']);
            $fc->field_bt_phone_type->setValue('movil');
            $fc->field_bt_phone->setValue($webform_submission->getData('phone'));
            $fc->setHostEntity($prospect);
            $fc->save();
            // Link field collection with prospect client object and save prospect in the CRM
            $prospect->field_bt_phones[] = ['field_collection_item' => $fc];
            $prospect->save();
        }
    }
}