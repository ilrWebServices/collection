<?php // $Id$
 
/**
 * @file collection.inc
 *
 * Provides collection content_types to modules implementing the
 * content_types plugin (panels being the major one).
 *
 */
 
/**
 * Callback function to supply a list of content types.
 *
 * This is only required to make CTools know that we're providing
 * multiple types here (since we have no single => TRUE in the array).
 *
 * The redundant naming is due to the ctools convention of naming the
 * functions <module>_<content_type>.
 */
function collection_collection_ctools_content_types() {
  return array(
    'title' => t('Collection'),
    'defaults' => array(
      'nid' => '',
      'teaser' => TRUE,
      'links' => FALSE,
    ),
  );
}
 
/**
 * Callback function to supply a list of content types.
 *
 * Defines content types, or rather, as this is a multiple content type
 * module, subtypes.
 */
function collection_collection_content_type_content_types() {
  $types = array(
    'collections' => array(
      'category' => t('Collection'),
      'title' => t('Collections'),
      'description' => t('The collection(s) the node is part of.'),
      'required context' => new ctools_context_required(t('node'), 'node'),
    ),
    'siblings' => array(
      'category' => t('Collection'),
      'title' => t('Collection siblings'),
      'description' => t('Listing of nodes in the same collections.'),
      'required context' => new ctools_context_required(t('node'), 'node'),
    ),
  );
 
  return $types;
}
 
/**
 * Return the pane contents.
 */
function collection_collection_content_type_render($subtype, $conf, $panel_args, $context) {
  $node = isset($context->data) ? drupal_clone($context->data) : NULL;
  if (!$node || !$node->collections) {
    return;
  }
  $block = new stdClass();
  $block->module = 'collection';
  $block->delta = $node->nid;
  
  switch($subtype) {
    case 'collections':
      $block->title = t('Collection listing');
      $block->content = collection_collection_listing($node, $conf['teaser'], $conf['links']);
      break;

    case 'siblings':
      $block->title = t('Collection siblings');
      $block->content = collection_sibling_listing($node, $conf['teaser'], $conf['links']);
      break;
  }
  return $block;
}

/**
 * Returns an edit form for the content type.
 *
 * Liberally 'borrowed' from ctools node.inc
 */
function collection_collection_content_type_edit_form(&$form, &$form_state) {
  $conf = $form_state['conf'];

  $form['teaser'] = array(
    '#title' => t('Teaser'),
    '#type' => 'checkbox',
    '#default_value' => $conf['teaser'],
    '#description' => t('Check here to show only the node teaser'),
  );

  $form['links'] = array(
    '#type' => 'checkbox',
    '#default_value' => $conf['links'],
    '#title' => t('Display links'),
    '#description' => t('Check here to display the links with the post.'),
  );
  
  return $form;
}

function collection_collection_content_type_edit_form_submit(&$form, &$form_state) {
  foreach (array('teaser', 'links') as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }
}

/**
 * Return the pane titles.
 */
function collection_collection_content_type_admin_title($subtype, $conf, $context) {
  switch($subtype) {

    case 'collections':
      return t('Collections for "@title"', array('@title' => $context->identifier));

    case 'siblings':
      return t('Sibling nodes for "@title"', array('@title' => $context->identifier));
  }
}
