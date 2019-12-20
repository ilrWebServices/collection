<?php

namespace Drupal\collection\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\collection\Event\CollectionEvents;
use Drupal\collection\Event\CollectionCreateEvent;

/**
 * Defines the Collection entity.
 *
 * @ingroup collection
 *
 * @ContentEntityType(
 *   id = "collection",
 *   label = @Translation("Collection"),
 *   label_collection = @Translation("Collections"),
 *   bundle_label = @Translation("Collection type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\collection\CollectionListBuilder",
 *     "views_data" = "Drupal\collection\Entity\CollectionViewsData",
 *     "translation" = "Drupal\collection\CollectionTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\collection\Form\CollectionForm",
 *       "add" = "Drupal\collection\Form\CollectionForm",
 *       "edit" = "Drupal\collection\Form\CollectionForm",
 *       "delete" = "Drupal\collection\Form\CollectionDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\collection\CollectionAccessControlHandler",
 *   },
 *   base_table = "collection",
 *   data_table = "collection_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer collections",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/collection/{collection}",
 *     "add-page" = "/collection/add",
 *     "add-form" = "/collection/add/{collection_type}",
 *     "edit-form" = "/collection/{collection}/edit",
 *     "delete-form" = "/collection/{collection}/delete",
 *     "collection" = "/admin/collections",
 *   },
 *   bundle_entity_type = "collection_type",
 *   field_ui_base_route = "entity.collection_type.edit_form"
 * )
 */
class Collection extends ContentEntityBase implements CollectionInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage);

    // Is the collection being inserted (e.g. is new)?
    if (!$update) {
      // Dispatch new collection event.
      $event = new CollectionCreateEvent($this);

      // Get the event_dispatcher service and dispatch the event.
      $event_dispatcher = \Drupal::service('event_dispatcher');
      $event_dispatcher->dispatch(CollectionEvents::COLLECTION_ENTITY_CREATE, $event);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getItems() {
    $collection_item_ids = \Drupal::entityQuery('collection_item')
      ->condition('collection', $this->id())
      ->sort('changed', 'DESC')
      ->execute();
    $items = $this->entityTypeManager()->getStorage('collection_item')->loadMultiple($collection_item_ids);
    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Owner'))
      ->setDescription(t('The user that owns this Collection.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Collection entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
