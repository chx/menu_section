<?php


namespace Drupal\menu_section\Theme;


use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\current_menu\Cache\CurrentMenuCacheContext;

class MenuSectionHelper {

  /**
   * @var \Drupal\current_menu\Cache\CurrentMenuCacheContext
   */
  protected $cacheContext;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  public function __construct(CurrentMenuCacheContext $cache_context, ConfigFactoryInterface $config_factory) {
    $this->cacheContext = $cache_context;
    $this->configFactory = $config_factory;
  }

  /**
   * @return FALSE|string
   */
  public function getSection() {
    if (preg_match('^(?<section>.*)(--\d+)$', $this->cacheContext->getContext(), $matches)) {
      foreach ($this->configFactory->get('menu_section.settings')->get('section_types') as $type) {
        if (preg_replace('/[^a-z0-9-]+/', '-', $type) === $matches['section']) {
          return $matches['section'];
        }
      }
    }
    return FALSE;
  }

}
