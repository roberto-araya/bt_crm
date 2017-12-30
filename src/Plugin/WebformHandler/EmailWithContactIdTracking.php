<?php

namespace Drupal\bt_crm\Plugin\WebformHandler;

use Drupal\webform\Plugin\WebformHandler\EmailWebformHandler;
//use Drupal\webform\WebformHandlerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\redhen_contact\Entity\Contact;
use \Drupal\field_collection\Entity\FieldCollectionItem;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\webform\WebformElementManagerInterface;
use Drupal\webform\WebformTokenManagerInterface;

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
     * EntityQuery for Redhen contacts.
     *
     * @var Drupal\Core\Entity\Query\QueryFactory
     */
    protected $entityQuery;


    /*
     *  Prospect Contact ID
     *
     */
    protected $id_contacto;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        array $configuration,
        $plugin_id, $plugin_definition,
        LoggerInterface $logger,
        EntityTypeManagerInterface $entity_type_manager,
        AccountInterface $current_user,
        ConfigFactoryInterface $config_factory,
        MailManagerInterface $mail_manager,
        WebformTokenManagerInterface $token_manager,
        WebformElementManagerInterface $element_manager,
        QueryFactory $entityQuery
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $logger, $entity_type_manager, $current_user, $config_factory, $mail_manager, $token_manager, $element_manager);
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
            $container->get('current_user'),
            $container->get('config.factory'),
            $container->get('plugin.manager.mail'),
            $container->get('webform.token_manager'),
            $container->get('plugin.manager.webform.element'),
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

            $this->id_contacto = $prospect->id();
        }else{
            $this->id_contacto = reset($entity_ids);
        }
    }

    public function preSave(WebformSubmissionInterface $webform_submission){
        $webform_submission->setData([
            'name' => $webform_submission->getData('name'),
            'email' => $webform_submission->getData('email'),
            'phone' => $webform_submission->getData('phone'),
            'id_contacto' => $this->id_contacto,
        ]);
    }
}