<?php

namespace Drupal\menu_section\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;

class MenuSectionConfig extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['menu_section.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_section_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $options = array_map(function (NodeType $nodeType) { return $nodeType->label(); }, NodeType::loadMultiple());
    $config = $this->config('menu_section.settings');
    $form['section_types'] = [
      '#totle' => t('The node types that create a menu section'),
      '#type' => 'checkboxes',
      '#options' => $options,
      '#default_value' => $config->get('section_types'),
    ];
    $form['allowed_types'] = [
      '#totle' => t('The node types that can go in a menu section'),
      '#type' => 'checkboxes',
      '#options' => $options,
      '#default_value' => $config->get('allowed_types'),
    ];
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $config = $this->config('menu_section.settings');
    $config->set('section_types', array_filter($form_state->getValue('section_types')));
    $config->set('allowed_types', array_filter($form_state->getValue('allowed_types')));
    $config->save();
  }

}
