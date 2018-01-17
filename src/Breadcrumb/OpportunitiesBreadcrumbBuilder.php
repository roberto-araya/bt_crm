<?php

namespace Drupal\bt_crm\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;

/**
 * Class OpportunitiesBreadcrumbBuilder.
 *
 * @package Drupal\bt_crm\Breadcrumb
 */
class OpportunitiesBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * The routes that will change their breadcrumbs.
   *
   * @var routes
   */
  private $routes = array(
    'page_manager.page_view_app_activities_opportunities_app_activities_opportunities-panels_variant-0',
    'entity.bt_opportunities.canonical',
    'entity.bt_opportunities.edit_form',
    'entity.bt_opportunities.delete_form',
    'page_manager.page_view_bt_create_opportunity_bt_create_opportunity-panels_variant-0',
  );

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $attributes) {
    $match = $this->routes;
    if (in_array($attributes->getRouteName(), $match)) {
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
    $site_name = \Drupal::config('system.site')->get('name');

    if ($route == 'page_manager.page_view_app_activities_opportunities_app_activities_opportunities-panels_variant-0') {
      $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
    }
    else {
      $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
      $breadcrumb->addLink(Link::createFromRoute('Opportunities', 'page_manager.page_view_app_activities_opportunities_app_activities_opportunities-panels_variant-0'));
    }

    return $breadcrumb;
  }

}
