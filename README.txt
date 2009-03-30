$Id$

OVERVIEW
========

The collection module provides a simple way to group nodes into a
'collection' with an associated node. For instance, a news site might
want to group articles together under a given topic, and allow users
to jump between articles.

FEATURES
========

* Use any node reference CCK field on the collection node to bind the
  nodes together.  

* Maintains a back reference from the nodes to the collection node for
  performance reasons.

* Provides a block that shows the collection nodes the currently
  viewed node is part of.

* Provides a block that lists the other nodes part of the same
  collections.

* Uses custom build_modes, allowing for custom CCK field display settings.

* Theming friendly blocks.

USAGE
=====

After installing the module, go to Administer > Content > Content types and edit
the content type that should function as a collection. Make sure the
content types has a node reference field, but you may configure the
field as you like.

Under Collection settings on the content type form, select the node
reference field and save the content type.

Now you may go to Administer > Build > Blocks and enable the
"Collection listing" and "In same collection(s)" blocks if you like.

The display of the collection nodes in the "Collection listing" block
may be customised using the usual node.tpl.php (or one of it's
overrides) template. The template may check that $node->build_mode ==
NODE_BUILD_COLLECTION to determine when the node is rendered in the
block.

Modules may change the ordering of collections in the block by
implementing hook_collection_alter($nodes, $node) (where $nodes is an
array of the collections and $node is the node in question), and
setting $collection->collection_weight to alter the order, and
$collection->collection_hidden to completely hide a given collection.

AUTHOR / MAINTAINER
===================
Thomas Fini Hansen (aka Xen.dk on Drupal.org)
Peytz & Co. (http://peytz.dk)
