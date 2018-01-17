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

    // Alter bt_activities routes.
    if ($route = $collection->get('entity.bt_activities.canonical')) {
      $route->setPath('/app/activity/{bt_activities}');
      $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('entity.bt_activities.edit_form')) {
      $route->setPath('/app/activity/{bt_activities}/edit');
      $route->setDefault('_title', 'Edit Activity');
      $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('entity.bt_activities.delete_form')) {
      $route->setPath('/app/activity/{bt_activities}/delete');
      $route->setOption('_admin_route', TRUE);
    }

    // Alter bt_opportunities routes.
    if ($route = $collection->get('entity.bt_opportunities.canonical')) {
      $route->setPath('/app/activities/opportunity/{bt_opportunities}');
      $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('entity.bt_opportunities.edit_form')) {
      $route->setPath('/app/activities/opportunity/{bt_opportunities}/edit');
      $route->setDefault('_title', 'Edit Opportunity');
      $route->setOption('_admin_route', TRUE);
    }
    if ($route = $collection->get('entity.bt_opportunities.delete_form')) {
      $route->setPath('/app/activities/opportunity/{bt_opportunities}/delete');
      $route->setOption('_admin_route', TRUE);
    }
  }

}
