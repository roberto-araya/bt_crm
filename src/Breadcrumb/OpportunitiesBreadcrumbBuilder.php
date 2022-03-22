<?php

namespace Drupal\bt_crm\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\Core\Config\ConfigFactory;

/**
 * Class OpportunitiesBreadcrumbBuilder.
 *
 * @package Drupal\bt_crm\Breadcrumb
 */
class OpportunitiesBreadcrumbBuilder implements BreadcrumbBuilderInterface {

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
    'entity.bt_opportunity.collection',
    'entity.bt_opportunity.canonical',
    'entity.bt_opportunity.edit_form',
    'entity.bt_opportunity.delete_form',
    'entity.bt_opportunity.add_form',
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

    if ($route == 'entity.bt_opportunity.collection') {
      $breadcrumb->addLink(Link::createFromRoute($this->siteName, 'bt_core.app'));
      $breadcrumb->addLink(Link::createFromRoute('Contacts', 'bt_crm.contacts'));
    }
    else {
      $breadcrumb->addLink(Link::createFromRoute($this->siteName, 'bt_core.app'));
      $breadcrumb->addLink(Link::createFromRoute('Contacts', 'bt_crm.contacts'));
      $breadcrumb->addLink(Link::createFromRoute('Opportunities', 'entity.bt_opportunity.collection'));
    }

    return $breadcrumb;
  }

}
