<?php

namespace Drupal\bt_crm\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // Alter redhen_contact routes.
    if ($route = $collection->get('entity.redhen_contact.add_page')) {
      $route->setPath('/app/contacts/add/people');
      $route->setDefault('_title', 'Add people');
    }
    if ($route = $collection->get('entity.redhen_contact.canonical')) {
      $route->setPath('/app/contacts/person/{redhen_contact}');
      $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('entity.redhen_contact.add_form')) {
      $route->setPath('/app/contacts/add/person/{redhen_contact_type}');
    }
    if ($route = $collection->get('entity.redhen_contact.edit_form')) {
      $route->setPath('/app/contacts/person/{redhen_contact}/edit');
      $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('entity.redhen_contact.delete_form')) {
      $route->setPath('/app/contacts/person/{redhen_contact}/delete');
      $route->setOption('_admin_route', TRUE);
    }

    // Alter redhen_org routes.
    if ($route = $collection->get('entity.redhen_org.add_page')) {
      $route->setPath('/app/contacts/organizations/add/organization');
      $route->setDefault('_title', 'Add organization');
    }
    if ($route = $collection->get('entity.redhen_org.canonical')) {
      $route->setPath('/app/contacts/organization/{redhen_org}');
      $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('entity.redhen_org.add_form')) {
      $route->setPath('/app/contacts/add/organization/{redhen_org_type}');
    }
    if ($route = $collection->get('entity.redhen_org.edit_form')) {
      $route->setPath('/app/contacts/organization/{redhen_org}/edit');
      $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('entity.redhen_org.delete_form')) {
      $route->setPath('/app/contacts/organization/{redhen_org}/delete');
      $route->setOption('_admin_route', TRUE);
    }
  }

}
