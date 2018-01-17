<?php

namespace Drupal\bt_crm\Plugin\Menu\LocalAction;

use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a local action plugin with a dynamic options.
 */
class CreateOpportunityLocalAction extends LocalActionDefault {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * The request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  private $request;

  /**
   * Constructs a "Create solicitude" object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteProviderInterface $route_provider
   *   The route provider.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteProviderInterface $route_provider, EntityTypeManagerInterface $entity_type_manager, Request $request) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $route_provider);
    $this->entityTypeManager = $entity_type_manager;
    $this->request = $request;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('router.route_provider'),
      $container->get('entity_type.manager'),
      $container->get('request_stack')->getCurrentRequest()
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(RouteMatchInterface $route_match) {
    $entityManager = $this->entityTypeManager;
    $request = $this->request;
    $options = parent::getOptions($route_match);
    $route = $request->attributes->get('_route');

    switch ($route) {
      case 'page_manager.page_view_app_activities_opportunities_app_activities_opportunities':
        break;

      case 'entity.redhen_contact.canonical':
        $eid = $request->attributes->get('_raw_variables')->get('redhen_contact');
        // Get label.
        $label = $entityManager->getStorage('redhen_contact')->load($eid)->label();
        // Add the prepopulate options.
        $options['query']['edit[field_bt_contact]'] = $label . ' (' . $eid . ')';
        break;

      case 'entity.redhen_org.canonical':
        $eid = $request->attributes->get('_raw_variables')->get('redhen_org');
        // Get label.
        $label = $entityManager->getStorage('redhen_org')->load($eid)->label();
        // Add the prepopulate options.
        $options['query']['edit[field_bt_organization]'] = $label . ' (' . $eid . ')';
        break;
    }

    return $options;
  }

}
