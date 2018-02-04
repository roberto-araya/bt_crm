<?php

namespace Drupal\bt_crm\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Core\Config\ConfigFactory;

/**
 * Class ActivitiesBreadcrumbBuilder.
 *
 * @package Drupal\bt_crm\Breadcrumb.
 */
class ActivitiesBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * The site name.
   *
   * @var string
   */
  private $siteName;

  /**
   * The routes that will change their breadcrumbs.
   *
   * @var array
   */
  private $routes = array(
    'page_manager.page_view_app_activities_app_activities-panels_variant-0',
    'bt_activities.add_page',
    'entity.bt_activities.canonical',
    'entity.bt_activities.edit_form',
    'entity.bt_activities.delete_form',
    'bt_activities.add',
  );

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->siteName = $configFactory->get('system.site')->get('name');
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {

    $match = $this->routes;
    if (in_array($route_match->getRouteName(), $match)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $route = $route_match->getRouteName();
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(["url"]);

    if ($route == 'page_manager.page_view_app_activities_app_activities-panels_variant-0') {
      $breadcrumb->addLink(Link::createFromRoute($this->siteName, 'page_manager.page_view_app_app-panels_variant-0'));
    }
    else {
      $breadcrumb->addLink(Link::createFromRoute($this->siteName, 'page_manager.page_view_app_app-panels_variant-0'));
      $breadcrumb->addLink(Link::createFromRoute('Activities', 'page_manager.page_view_app_activities_app_activities-panels_variant-0'));
    }
    if ($route == 'bt_activities.add') {
      $breadcrumb->addLink(Link::createFromRoute('Schedule Activity', 'bt_activities.add_page'));
    }

    return $breadcrumb;
  }

}
