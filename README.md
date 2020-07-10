# Collection

## Introduction

The Collection module allows users to organize any content or configuration entity into arbitrary collections.

Examples include _blogs_ (collections of posts), _periodicals_ (collections of articles or stories), _subsites_ (collections of pages, along with a related menu, in a discreet section of the site) and _personal collections_ of content or configuration of interest to individual users.

Multiple extension points, included events and hooks, allow developers to implement the specific requirements of their use cases.

Collection has some similarities to the Group module (https://www.drupal.org/project/group), in that it uses Collection item entities as relation objects to join content and configuration to Collections. But Collection does not enable custom permissions and roles per collection, and Collection allows users to place configuration entities, such as menus, into collections.


## Requirements

This module requires the following modules:

- dynamic_entity_reference (https://www.drupal.org/project/dynamic_entity_reference)
- drupal:path
- key_value_field (https://www.drupal.org/project/key_value_field)

## Installation

Install as you would normally install a contributed Drupal module. Visit https://www.drupal.org/node/1897420 for further information.

## Configuration



Collection types

Collection item types

Ownership (& seeing one's )

Permissions



## Listings

Currently, sites using the paragraphs module can create filtered listings of items in a collection. To enable this feature, simply add a collection entity reference field to a paragraph type, and then enable the collection listing behavior for that type.

When adding a paragraph item of this type, there will be a new "Behaviors" tab in the UI that allows you to specify the content types, bundles and display modes to include when that listing is rendered, as well as the number of items.

### Required Patches for Listings

https://www.drupal.org/files/issues/core-patch-2915512-7.patch
https://www.drupal.org/files/issues/2020-03-19/3120952-2.patch

## Roadmap

- Customize DER allowed referenced per collection or collection item type. Research how node module allows base field overrides to be stored in separate config items. See https://drupal.stackexchange.com/questions/253257/how-to-easily-alter-an-entitys-base-field-definition-per-bundle
- Implement user interface for adding items to collection (e.g. contextual links, custom block, node add/edit form).
- Add 'in collection' condition and admin block (maybe).
- Implement exposed filters in the collection item listing. Investigate using Views as the listbuilder.
- Allow bulk operations on collection item lists.
- Fix Views integration.
- Add collection item listing to collection pages.
- Improve collection permissions
  - per collection type add/edit/delete
- Offer to remove collection items when deleting a collection
  - Or prevent deletion of collections with items
- Add tests
