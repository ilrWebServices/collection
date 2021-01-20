<?php

/**
 * @file
 * Post update functions for the Collection module.
 */

/**
 * Update all collection_items to update labels.
 */
function collection_post_update_collection_item_labels(&$sandbox) {
  $item_storage = \Drupal::service('entity_type.manager')->getStorage('collection_item');
  $collection_items = $item_storage->loadMultiple();

  foreach ($collection_items as $collection_item) {
    // This should trigger CollectionItem::preSave() and update the
    // collection_item label.
    $collection_item->save();
  }
}
