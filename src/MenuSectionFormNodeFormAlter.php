<?php

namespace Drupal\menu_section;

use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\node\NodeInterface;

class MenuSectionFormNodeFormAlter {

  /**
   * @param $form
   * @param $node
   */
  public static function alter(&$form, NodeInterface $node) {
    /** @var \Drupal\node\NodeTypeInterface $nodeType */
    $nodeType = $node->type->entity;
    if (\Drupal::routeMatch()->getRouteName() == NodeAddSectionMenuRoutes::ROUTE_NAME_PREFIX . $nodeType->id()) {
      $menu = \Drupal::routeMatch()->getRawParameter('menu');
    }
    if (empty($menu) && $node->id()) {
      $query = \Drupal::entityQuery('menu_link_content')->condition('link.uri', 'entity:node/' . $node->id());
      if ($available = $nodeType->getThirdPartySetting('menu_ui', 'available_menus', ['main'])) {
        $query->condition('menu_name', array_values($available), 'NOT IN');
      }
      $linkIds = $query->execute();
      if ($linkIds) {
        $menu = MenuLinkContent::load(reset($linkIds))->getMenuName();
      }
    }
    // menu_ui_form_node_form_alter() only cares about these settings so no
    // need to store anything or a config overrider etc. It's enough to enforce
    // the menu section name to the existing one if there's one.
    if (!empty($menu)) {
      $form['#process'][] = get_class() . '::process';
      $nodeType->setThirdPartySetting('menu_ui', 'available_menus', [$menu]);
      $nodeType->setThirdPartySetting('menu_ui', 'parent', "$menu:");
    }
  }

  public static function process($form) {
    $form['menu']['enabled'] = ['#type' => 'value', '#value' => TRUE];
    unset($form['menu']['link']['#states']);
    $form['menu']['#open'] = TRUE;
    $form['menu']['link']['title']['#required'] = TRUE;
    return $form;
  }

}
