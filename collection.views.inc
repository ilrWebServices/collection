<?php

/**
 * Implements hook_views_data().
 *
 * Add fields to the view that mirror the CollectionItemListBuilder.
 */
function collection_views_data() {
  $data['collection_item']['entity_type_label'] = [
    'title' => t('Collected item type'),
    'field' => [
      'id' => 'collection_item_collected_item_entity_type_label',
    ],
  ];

  $data['collection_item']['status'] = [
    'title' => t('Item status'),
    'field' => [
      'id' => 'collection_item_collected_item_status',
    ],
  ];

  return $data;
}
