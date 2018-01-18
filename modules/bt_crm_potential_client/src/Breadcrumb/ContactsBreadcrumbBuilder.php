<?php

namespace Drupal\bt_crm_potential_client\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;

/**
 * Class ContactsBreadcrumbBuilder.
 *
 * @package Drupal\bt_crm_potential_client\Breadcrumb
 */
class ContactsBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * The routes that will change their breadcrumbs.
   *
   * @var array
   */
  private $routes = array(
    'page_manager.page_view_bt_add_potential_client_bt_add_potential_client-panels_variant-0',
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
  public function build() {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(["url"]);
    $site_name = \Drupal::config('system.site')->get('name');

    $breadcrumb->addLink(Link::createFromRoute($site_name, 'page_manager.page_view_app_app-panels_variant-0'));
    $breadcrumb->addLink(Link::createFromRoute('Contacts', 'page_manager.page_view_app_contacts_app_contacts-panels_variant-0'));
    $breadcrumb->addLink(Link::createFromRoute('Persons', 'page_manager.page_view_app_persons_app_persons-panels_variant-0'));
    $breadcrumb->addLink(Link::createFromRoute('Add Person', 'entity.redhen_contact.add_page'));

    return $breadcrumb;
  }

}
