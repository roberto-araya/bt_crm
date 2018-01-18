<?php

namespace Drupal\bt_crm\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;

/**
 * Class ActivitiesBreadcrumbBuilder.
 *
 * @package Drupal\bt_crm\Breadcrumb.
 */
class ActivitiesBreadcrumbBuilder implements BreadcrumbBuilderInterface {

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
    'page_manager.page_view_bt_schedule_email_bt_schedule_email-panels_variant-0',
    'page_manager.page_view_bt_schedule_phone_call_bt_schedule_phone_call-panels_variant-0',
    'page_manager.page_view_bt_schedule_meeting_bt_schedule_meeting-panels_variant-0',
    'page_manager.page_view_bt_schedule_visit_bt_schedule_visit-panels_variant-0',
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

    if ($route == 'page_manager.page_view_app_activities_app_activities-panels_variant-0') {
      $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
    }
    else {
      $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
      $breadcrumb->addLink(Link::createFromRoute('Activities', 'page_manager.page_view_app_activities_app_activities-panels_variant-0'));
    }
    if (preg_match("/page_manager.page_view_bt_schedule_/", $route)) {
      $breadcrumb->addLink(Link::createFromRoute('Schedule Activity', 'bt_activities.add_page'));
    }

    return $breadcrumb;
  }

}
