<?php

namespace Drupal\bt_crm\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;
use Drupal\redhen_contact\Entity\Contact;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Converts potential clients to clients.
 *
 * @package Drupal\bt_crm\Form
 */
class ConvertPotentialToClientConfirm extends FormBase {

  /**
   * Redhen contact object.
   *
   * @var \Drupal\redhen_contact\Entity\Contact
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
  public function buildForm(array $form, FormStateInterface $form_state, Contact $redhen_contact = NULL) {
    $this->contact = $redhen_contact;
    $email = $this->contact->getEmail();
    if (empty($email)) {
      $title = $this->t('This contact cannot be converted');
      $description = $this->t('The contact must have an email. Edit the contact, add an email and try to convert again.');
      $actions = [
        'cancel' => [
          '#type' => 'link',
          '#title' => $this->t('Accept'),
          '#attributes' => [
            'class' => ['button'],
          ],
          '#url' => Url::fromRoute('bt_crm.persons'),
          '#cache' => [
            'contexts' => ['url.query_args:destination'],
          ],
        ],
      ];
    }
    else {
      $title = $this->t('Are you sure you want to convert the contact %type in client?', ['%type' => $this->contact->label()]);
      $description = $this->t('This action will convert the current contact of type potential client to a contact of type client. All activities and oportunities linked to the contact will remains linked to new contact.');
      $actions = [
        'submit' => [
          '#type' => 'submit',
          '#value' => $this->t('Convert'),
          '#button_type' => 'primary',
          '#submit' => [
            [$this, 'submitForm'],
          ],
        ],
        'cancel' => [
          '#type' => 'link',
          '#title' => $this->t('Cancel'),
          '#attributes' => [
            'class' => ['button'],
          ],
          '#url' => Url::fromRoute('bt_crm.persons'),
          '#cache' => [
            'contexts' => ['url.query_args:destination'],
          ],
        ],
      ];
    }
    $form['#title'] = $title;
    $form['#attributes']['class'][] = 'confirmation';
    $form['description'] = ['#markup' => $description];

    $form[$this->getFormName()] = ['#type' => 'hidden', '#value' => 1];
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

    $this->contact->set('uid', [$user->id()]);
    $this->contact->save();
  }

}
