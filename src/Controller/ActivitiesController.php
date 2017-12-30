<?php

namespace Drupal\bt_crm\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\eck\Entity\EckEntityBundle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ActivitiesController extends ControllerBase
{

    /**
     * Displays add content link for available entity types.
     *
     * @param
     *
     * @return array
     *   The output as a renderable array.
     */
    public function addPage($contact_id, $opportunity_id) {
        $content = [];
        $entityTypeBundle = "bt_activities_type";
        $entityTypeManager = $this->entityTypeManager();

        /** @var EckEntityBundle $bundle */
        foreach ($entityTypeManager->getStorage($entityTypeBundle)->loadMultiple() as $bundle) {
            if ($entityTypeManager->getAccessControlHandler('bt_activities')->createAccess($bundle->type)) {
                $content[$bundle->type] = $bundle;
            }
        }

        $return = [
            '#theme' => 'activities_add_list',
            '#content' => $content,
            '#entity_type' => [
                'id' => 'bt_activities',
                'label' => 'Activities',
            ],
            '#entity_info' => array()
        ];
        if ($contact_id > 0) {
            $contact = $entityTypeManager->getStorage('redhen_contact')->load($contact_id);
            $return['#entity_info']['contact_id'] = $contact_id;
            $return['#entity_info']['contact_label'] = $contact->label();
        }
        if ($opportunity_id > 0) {
            $opportunity = $entityTypeManager->getStorage('bt_opportunities')->load($opportunity_id);
            $return['#entity_info']['opportunity_id'] = $opportunity_id;
            $return['#entity_info']['opportunity_label'] = $opportunity->label();
        }
        //ksm($return['#entity_info']);
        return $return;
    }

    /**
     * Title callback for add page.
     *
     * @param .
     *
     * @return string
     *   The title.
     */
    public function addPageTitle() {
        return $this->t('Schedule Activity');
    }
}