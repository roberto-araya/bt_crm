<?php

namespace Drupal\bt_crm\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for the opportunity entity edit forms.
 */
class BtOpportunityForm extends ContentEntityForm {

  /**
   * Renderer Service.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Injecting the Renderer Service.
   *
   * @param \Drupal\Core\Render\Renderer $renderer
   *   Renderer Service.
   */
  public function __construct(Renderer $renderer) {
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => $this->renderer->render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New opportunity %label has been created.', $message_arguments));
      $this->logger('bt_crm')->notice('Created new opportunity %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The opportunity %label has been updated.', $message_arguments));
      $this->logger('bt_crm')->notice('Updated new opportunity %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.bt_opportunity.canonical', ['bt_opportunity' => $entity->id()]);
    return $result;
  }

}
