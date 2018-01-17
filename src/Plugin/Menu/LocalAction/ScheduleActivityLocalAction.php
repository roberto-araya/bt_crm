<?php

namespace Drupal\bt_crm\Plugin\Menu\LocalAction;

use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a local action plugin with a dynamic options.
 */
class ScheduleActivityLocalAction extends LocalActionDefault {

  /**
   * The request.
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
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteProviderInterface $route_provider, Request $request) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $route_provider);
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
      $container->get('request_stack')->getCurrentRequest()
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {
    $request = $this->request;
    $parameters = parent::getRouteParameters($route_match);
    $route = $request->attributes->get('_route');

    switch ($route) {
      case 'page_manager.page_view_app_activities_app_activities':
        break;

      case 'entity.redhen_contact.canonical';
        $parameters['contact_id'] = $request->attributes->get('_raw_variables')->get('redhen_contact');
        break;

      case 'entity.bt_opportunities.canonical':
        $values = $request->attributes->get('bt_opportunities')->get('field_bt_contact')->get(0)->getValue();
        $parameters['contact_id'] = $values['target_id'];
        $parameters['opportunity_id'] = $request->attributes->get('_raw_variables')->get('bt_opportunities');
        break;
    }

    return $parameters;
  }

}
