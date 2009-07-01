<?php
// $Id$

/**
 * @file
 *
 * Plugin to provide an relationship handler for collections
 */

/**
 * Implementation of specially named hook_ctools_relationships().
 */
function collection_collection_node_ctools_relationships() {
  $args['collection_node'] = array(
    'title' => t("Collection node from node"),
    'keyword' => 'collection_node',
    'description' => t('Adds the first collection the node is part of as context.'),
    'required context' => new ctools_context_required(t('Node'), 'node'),
    'context' => 'collection_collection_node_context',
    'settings form' => 'collection_collection_node_settings_form',
  );
  return $args;
}

/**
 * Return a new context based on an existing context
 */
function collection_collection_node_context($context = NULL, $conf) {
  // If unset it wants a generic, unfilled context, which is just NULL
  if (empty($context->data)) {
    return ctools_context_create_empty('node');
  }

  if ($context->data->collections) {
    return ctools_context_create('node', $context->data->collections[0]);
  }
  else {
    if ($conf['use_self']) {
      return ctools_context_create('node', $context->data);
    }
    return ctools_context_create_empty('node');
  }
}

/**
 * Settings form for the relationship
 */
function collection_collection_node_settings_form($conf) {
  $form['use_self'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use the node itself if not part of any collections'),
    '#default_value' => $conf['use_self'],
  );

  return $form;
}

