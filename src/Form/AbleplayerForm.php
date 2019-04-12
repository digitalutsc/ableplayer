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
   public function validateForm(array &$form, FormStateInterface $form_state) {

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
    $form = parent::buildForm($form, $form_state);
   // Default settings.
   $config = $this->config('ableplayer.settings');


    $form['ableplayer_youtube_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('YouTube Data API Key'),
      '#default_value' => $config->get('ableplayer_youtube_api_key'),
      '#description' => $this->t('Get a YouTube Data API key by registering your application at the Google Developer Console. For complete instructions, see Google\'s Getting Started page. Note: All that\'s needed for playing YouTube videos in Able Player is a simple API key, not OAuth 2.0.'),
    ];

    return parent::buildForm($form, $form_state);
  }

}
?>
