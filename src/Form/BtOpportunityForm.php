<?php

namespace Drupal\bt_crm\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
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
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   Entity Interface.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface|null $entity_type_bundle_info
   *   Entity Bundle Info Interface.
   * @param \Drupal\Component\Datetime\TimeInterface|null $time
   *   Time Interface.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   Renderer Service.
   */
  public function __construct(EntityRepositoryInterface $entity_repository, EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL, TimeInterface $time = NULL, Renderer $renderer) {
    parent::__construct($entity_repository, $entity_type_bundle_info, $time);
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.repository'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time'),
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
