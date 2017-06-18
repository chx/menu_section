<?php

namespace Drupal\menu_section\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeTypeInterface;
use Drupal\system\MenuInterface;

class MenuSectionNodeAddAccessCheck {

  public function access(AccountInterface $account, NodeTypeInterface $node_type = NULL, MenuInterface $menu = NULL) {
    if ($node_type && $menu) {
      $uid = $menu->getThirdPartySetting('menu_section', 'uid');
      $allowedTypes = \Drupal::config('menu_section.settings')->get('allowed_types');
      if (in_array($node_type->id(), $allowedTypes) && isset($uid) && (int) $menu->getThirdPartySetting('menu_section', 'uid') === (int) $account->id()) {
        return AccessResult::allowed();
      }
    }
    return \Drupal::service('access_check.node.add')->access($account, $node_type);
  }

}
