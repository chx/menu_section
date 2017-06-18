<?php

namespace Drupal\menu_section;

use Drupal\node\Entity\NodeType;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class NodeAddSectionMenuRoutes {

  const ROUTE_NAME_PREFIX = "menu_section.node.add.";

  public function routes() {
    $defaults = [
      '_controller' => '\Drupal\node\Controller\NodeController::add',
      '_title_callback' => '\Drupal\node\Controller\NodeController::addPageTitle',
    ];
    $requirements = [
      '_menu_section_node_add_access' => 'node:{node_type}:{menu}',
    ];
    $options = [
      '_node_operation_route' => TRUE,
      'parameters' => [
        'node_type' => [
          'with_config_overrides' => TRUE,
        ],
        'menu' => [
          'type' => 'entity:menu',
          'with_config_overrides' => TRUE,
        ],
      ],
    ];
    $routeCollection = new RouteCollection();
    foreach (array_keys(NodeType::loadMultiple()) as $nodeType) {
      $defaults['node_type'] = $nodeType;
      $routeCollection->add(static::ROUTE_NAME_PREFIX . $nodeType, new Route("/node/add/$nodeType/{menu}", $defaults, $requirements, $options));
    }
    return $routeCollection;
  }

}
