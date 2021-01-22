<?php

namespace Drupal\collection\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Defines dynamic local tasks for collections.
 */
class DynamicLocalActions extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    // Add the 'new content' action link to collection_item listings.
    // %todo: Only add if the collection allows nodes.
    $this->derivatives['entity.collection.new_content'] = $base_plugin_definition;
    $this->derivatives['entity.collection.new_content']['title'] = 'Add new content';
    $this->derivatives['entity.collection.new_content']['route_name'] = 'collection_item.new.node';
    $this->derivatives['entity.collection.new_content']['appears_on'] = [
      'entity.collection_item.collection',
      'view.collection_items.page_1',
    ];

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
