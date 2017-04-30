<?php

namespace Drupal\js_post_load\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class JsPostLoadSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'js_post_load_settings';
  }

  protected function getEditableConfigNames() {
    return ['js_post_load.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('js_post_load.settings');

    $form['js_post_load_enabled'] = [
      '#type' => 'checkbox',
      '#title' => t('Enabled'),
      '#default_value' => $config->get('enabled'),
      '#description' => t('Enable JS Post Load for all anonymous visits. You must manually rebuild Drupal cache when this value changes'),
    ];

    $form['js_post_load_excluded_ids'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Excluded entities ids'),
      '#required' => FALSE,
      '#description' => t('Enter ids of entities (one per line) which should not be processed. These nodes will load JS as usual.'),
      '#default_value' => $config->get('excluded_ids'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')->getEditable('js_post_load.settings');
    $config
      ->set('enabled', $form_state->getValue('js_post_load_enabled'))
      ->set('enabled_content_types', $form_state->getValue('js_post_load_enabled_content_types'))
      ->set('excluded_ids', $form_state->getValue('js_post_load_excluded_ids'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
