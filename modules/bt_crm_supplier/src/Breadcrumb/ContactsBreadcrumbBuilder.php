<?php

namespace Drupal\bt_crm_supplier\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;

/**
 * Class ContactsBreadcrumbBuilder.
 *
 * @package Drupal\bt_crm_supplier\Breadcrumb
 */
class ContactsBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * The routes that will change their breadcrumbs.
   *
   * @var routes
   */
  private $routes = array(
    'page_manager.page_view_bt_add_supplier_bt_add_supplier-panels_variant-0',
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
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(["url"]);
    $site_name = \Drupal::config('system.site')->get('name');

    $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
    $breadcrumb->addLink(Link::createFromRoute('Contacts', 'page_manager.page_view_app_contacts_app_contacts-panels_variant-0'));
    $breadcrumb->addLink(Link::createFromRoute('Organizations', 'page_manager.page_view_app_organizations_app_organizations-panels_variant-0'));
    $breadcrumb->addLink(Link::createFromRoute('Add Organization', 'entity.redhen_org.add_page'));

    return $breadcrumb;
  }

}
