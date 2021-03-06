<?php

namespace Drupal\collection;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * .
 *
 * @ingroup collection
 */
class ContentEntityCollectionListBuilder extends EntityListBuilder {

  protected $collectionItems;

  /**
   * Constructs a new EntityListBuilder object.
   *
   * @param array $collection_items
   *   An array of collection item entities.
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   */
  public function __construct(array $collection_items, EntityTypeInterface $entity_type) {
    $this->collectionItems = $collection_items;
    $this->entityType = $entity_type;
  }

  /**
   * Loads entity IDs using a pager sorted by the entity id.
   *
   * @return array
   *   An array of entity IDs.
   */
  public function load() {
    return $this->collectionItems;
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(EntityInterface $entity) {
    $operations = $this->getDefaultOperations($entity);
    $operations += $this->moduleHandler()->invokeAll('entity_operation', [$entity]);
    $this->moduleHandler->alter('entity_operation', $operations, $entity);

    // Remove the edit_item added in collection_entity_operation().
    if (isset($operations['edit_item'])) {
      unset($operations['edit_item']);
    }

    uasort($operations, '\Drupal\Component\Utility\SortArray::sortByWeightElement');
    return $operations;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['item'] = $this->t('Collection');
    $header['canonical'] = $this->t('Canonical');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\collection\Entity\CollectionItem $entity */
    $row['item'] = $entity->collection->entity->toLink();
    $row['canonical'] = $entity->canonical->value ? $this->t('Yes') : $this->t('No');
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();
    $build['table']['#empty'] = $this->t('This content has not been added to any collections.');
    return $build;
  }

}
