<?php

namespace Drupal\bt_crm\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;

/**
 * Class ContactsBreadcrumbBuilder.
 *
 * @package Drupal\bt_crm\Breadcrumb
 */
class ContactsBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * The routes that will change their breadcrumbs.
   *
   * @var array
   */
  private $routes = array(
    'page_manager.page_view_app_contacts_app_contacts-panels_variant-0',
    'page_manager.page_view_app_persons_app_persons-panels_variant-0',
    'entity.redhen_contact.canonical',
    'entity.redhen_contact.add_page',
    'entity.redhen_contact.edit_form',
    'entity.redhen_contact.delete_form',
    'page_manager.page_view_app_organizations_app_organizations-panels_variant-0',
    'entity.redhen_org.canonical',
    'entity.redhen_org.add_page',
    'entity.redhen_org.edit_form',
    'entity.redhen_org.delete_form',
    'page_manager.page_view_bt_add_supplier',
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

    if ($route == 'page_manager.page_view_app_contacts_app_contacts-panels_variant-0') {
      $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
    }
    else {
      $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
      $breadcrumb->addLink(Link::createFromRoute('Contacts', 'page_manager.page_view_app_contacts_app_contacts-panels_variant-0'));
    }
    if (preg_match("/entity.redhen_contact./", $route)) {
      $breadcrumb->addLink(Link::createFromRoute('Persons', 'page_manager.page_view_app_persons_app_persons-panels_variant-0'));
    }

    if (preg_match("/entity.redhen_org./", $route)) {
      $breadcrumb->addLink(Link::createFromRoute('Organizations', 'page_manager.page_view_app_organizations_app_organizations-panels_variant-0'));
    }

    if (in_array($route, ['page_manager.page_view_bt_add_company', 'page_manager.page_view_bt_add_supplier'])) {
      $breadcrumb->addLink(Link::createFromRoute('Organizations', 'page_manager.page_view_app_organizations_app_organizations-panels_variant-0'));
      $breadcrumb->addLink(Link::createFromRoute('Add Organization', 'entity.redhen_org.add_page'));
    }

    return $breadcrumb;
  }

}
