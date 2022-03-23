<?php

namespace Drupal\bt_crm\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Core\Config\ConfigFactory;

/**
 * Contacts breadcrumbs.
 *
 * @package Drupal\bt_crm\Breadcrumb
 */
class ContactsBreadcrumbBuilder implements BreadcrumbBuilderInterface {

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
  private $routes = [
    'bt_crm.contacts',
    'bt_crm.persons',
    'entity.redhen_contact.canonical',
    'entity.redhen_contact.add_form',
    'entity.redhen_contact.add_page',
    'entity.redhen_contact.edit_form',
    'entity.redhen_contact.delete_form',
    'bt_crm.organizations',
    'entity.redhen_org.add_form',
    'entity.redhen_org.canonical',
    'entity.redhen_org.add_page',
    'entity.redhen_org.edit_form',
    'entity.redhen_org.delete_form',
  ];

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

    if ($route == 'bt_crm.contacts') {
      $breadcrumb->addLink(Link::createFromRoute($this->siteName, 'bt_core.app'));
    }
    else {
      $breadcrumb->addLink(Link::createFromRoute($this->siteName, 'bt_core.app'));
      $breadcrumb->addLink(Link::createFromRoute('Contacts', 'bt_crm.contacts'));
    }
    if (preg_match("/entity.redhen_contact./", $route)) {
      $breadcrumb->addLink(Link::createFromRoute('Persons', 'bt_crm.persons'));

      if ($route == 'entity.redhen_contact.add_form') {
        $breadcrumb->addLink(Link::createFromRoute('Add Person', 'entity.redhen_contact.add_page'));
      }
    }

    if (preg_match("/entity.redhen_org./", $route)) {
      $breadcrumb->addLink(Link::createFromRoute('Organizations', 'bt_crm.organizations'));

      if ($route == 'entity.redhen_org.add_form') {
        $breadcrumb->addLink(Link::createFromRoute('Add Organization', 'entity.redhen_org.add_page'));
      }
    }

    return $breadcrumb;
  }

}
