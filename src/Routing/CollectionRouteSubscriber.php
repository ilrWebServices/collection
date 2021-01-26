<?php

namespace Drupal\collection\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class CollectionRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $route_collection) {
    foreach ($route_collection->all() as $route) {
      $path = $route->getPath();

      if ($path === '/collection/{collection}/items') {
        // @todo Remove if https://www.drupal.org/i/2719797 lands.
        $route->setOption('_admin_route', TRUE);

        //  @todo Remove if https://www.drupal.org/i/2528166 lands.
        if (!$route->getOption('parameters')) {
          $route->setOption('parameters', [
            'collection' => [
              'type' => 'entity:collection',
            ],
          ]);
        }
      }
    }
  }

}
