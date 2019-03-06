<?php

/**
 * @file
 * Contains \Drupal\ableplayer\Form\AbleplayerForm.
 */

namespace Drupal\ableplayer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class AbleplayerForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ableplayer_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('ableplayer.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ableplayer.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form = [];

    $form['ableplayer_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => t('Able Player'),
      '#collapsible' => FALSE,
    ];

    $form['ableplayer_fieldset']['ableplayer_compression_level'] = [
      '#type' => 'radios',
      '#title' => t('Compression level'),
      '#description' => t('Development provides a human-readable version of JavaScript and CSS.
      Production combines and minifies both to conserve bandwidth.'),
      '#default_value' => variable_get('ableplayer_compression_level', 1),
      '#options' => [
        0 => t('Development'),
        1 => t('Production'),
      ],
    ];

    $form['ableplayer_fieldset']['ableplayer_test_fallback'] = [
      '#type' => 'checkbox',
      '#title' => t('Test Fallback'),
      '#description' => t('Force Able Player to load the fallback player (jwplayer). This is recommended for testing purposes only.'),
      '#default_value' => variable_get('ableplayer_test_fallback', 0),
    ];

    return parent::buildForm($form, $form_state);
  }

}
?>
