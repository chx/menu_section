<?php

namespace Drupal\menu_section;


use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\node\Entity\NodeType;

class MenuSectionContextualLinks extends DeriverBase {

  public function getDerivativeDefinitions($base_plugin_definition) {
    $definitions = [];
    /** @var \Drupal\node\NodeTypeInterface $nodeType */
    foreach (NodeType::loadMultiple() as $nodeType) {
      $definitions[] =[
        'title' => t('Add @label', ['@label' => $nodeType->label()]),
        'route_name' => 'menu_section.node.add.' . $nodeType->id(),
        'group' => 'menu',
        'id' => 'menu_section.node.add.' . $nodeType->id(),
      ];
    }
    return $definitions;
  }

}
