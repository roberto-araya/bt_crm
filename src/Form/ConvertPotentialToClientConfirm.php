<?php

namespace Drupal\bt_crm\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;
use Drupal\redhen_contact\ContactInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Class ConvertPotentialToClientConfirm.
 *
 * @package Drupal\bt_crm\Form
 */
class ConvertPotentialToClientConfirm extends FormBase {

  /**
   * Redhen contact object.
   *
   * @var contact
   */
  public $contact;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'convert_potential_client_to_client_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getFormName() {
    return 'confirm_convert_contact';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ContactInterface $redhen_contact = NULL) {
    $this->contact = $redhen_contact;
    $email = $this->contact->getEmail();
    if (empty($email)) {
      $title = t('This contact cannot be converted');
      $description = t('The contact must have an email. Edit the contact, add an email and try to convert again.');
      $actions = array(
        'cancel' => array(
          '#type' => 'link',
          '#title' => t('Accept'),
          '#attributes' => array(
            'class' => array('button'),
          ),
          '#url' => Url::fromRoute('bt_crm.persons'),
          '#cache' => array(
            'contexts' => array(
              'url.query_args:destination',
            ),
          ),
        ),
      );
    }
    else {
      $title = t('Are you sure you want to convert the contact %type in client?', array('%type' => $this->contact->label()));
      $description = t('This action will convert the current contact of type potential client to a contact of type client. All activities and oportunities linked to the contact will remains linked to new contact.');
      $actions = array(
        'submit' => array(
          '#type' => 'submit',
          '#value' => t('Convert'),
          '#button_type' => 'primary',
          '#submit' => array(
            array($this, 'submitForm'),
          ),
        ),
        'cancel' => array(
          '#type' => 'link',
          '#title' => t('Cancel'),
          '#attributes' => array(
            'class' => array('button'),
          ),
          '#url' => Url::fromRoute('bt_crm.persons'),
          '#cache' => array(
            'contexts' => array(
              'url.query_args:destination',
            ),
          ),
        ),
      );
    }
    $form['#title'] = $title;
    $form['#attributes']['class'][] = 'confirmation';
    $form['description'] = array('#markup' => $description);

    $form[$this->getFormName()] = array('#type' => 'hidden', '#value' => 1);
    $form['#theme'] = 'confirm_form';

    $form['actions'] = $actions;

    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * This is the default entity object builder function. It is called before any
   * other submit handler to build the new entity object to be used by the
   * following submit handlers. At this point of the form workflow the entity is
   * validated and the form state can be updated, this way the subsequently
   * invoked handlers can retrieve a regular entity object to act on. Generally
   * this method should not be overridden unless the entity requires the same
   * preparation for two actions, see \Drupal\comment\CommentForm for an example
   * with the save and preview actions.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->contact->set('type', 'bt_client');
    $email = $this->contact->getEmail();
    $raw_name = $this->contact->getFullName();
    $name = substr(str_replace(" ", "-", trim(strtolower($raw_name))), 0, 60);

    $user = User::create();
    $user->setPassword("password");
    $user->enforceIsNew();
    $user->setEmail($email);
    $user->setUsername($name);
    $user->addRole('bt_client');
    $user->activate();
    $user->save();

    $this->contact->set('uid', array($user->id()));
    $this->contact->save();
  }

}
