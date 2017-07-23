<?php

namespace Drupal\menu_section\Theme;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;
use Drupal\current_menu\Cache\CurrentMenuCacheContext;

/**
 * Negotiate a theme based on a configured prefix and the current menu section.
 */
class MenuSectionThemeNegotiator implements ThemeNegotiatorInterface {

  /**
   * @var \Drupal\menu_section\Theme\MenuSectionHelper
   */
  protected $helper;

  /**
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;


  public function __construct(MenuSectionHelper $helper, ThemeHandlerInterface $theme_handler, ConfigFactoryInterface $config_factory) {
    $this->helper = $helper;
    $this->themeHandler = $theme_handler;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return (bool) $this->determineActiveTheme($route_match);
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    $theme_prefix = $this->configFactory->get('menu_section.settings')->get('theme_prefix');
    $section = $this->helper->getSection();
    if ($theme_prefix && $section) {
      $theme = $theme_prefix . '_' . $section;
      if ($this->themeHandler->themeExists($theme)) {
        return $theme;
      }
    }
  }
}
