<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

use Drupal\file\Plugin\Field\FieldFormatter\FileMediaFormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Template\Attribute;

/**
 * Plugin implementation of the 'ableplayer_video' formatter.
 *
 * @FieldFormatter(
 *   id = "ableplayer_video",
 *   label = @Translation("Able Player"),
 *   description = @Translation("Display the file using Able Player."),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class AbleplayerFormatter extends FileMediaFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function getMediaType() {
    return 'video';
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'autoplay' => FALSE,
      'loop' => FALSE,
	    'data-speed-icons' => 'arrows'
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      'autoplay' => [
        '#title' => $this->t('Autoplay'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('autoplay'),
      ],
      'loop' => [
        '#title' => $this->t('Loop'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('loop'),
      ],
      'data-speed-icons' => [
        '#title' => $this->t('Speed icons'),
        '#type' => 'radios',
        '#options' => [
          'animals' => $this->t('Use animal icons (rabbit and turtle)'),
          'arrows' => $this->t('Use up and down arrow icons'),
        ],
        '#default_value' => $this->getSetting('data-speed-icons'),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Autoplay: %autoplay', ['%autoplay' => $this->getSetting('autoplay') ? $this->t('yes') : $this->t('no')]);
    $summary[] = $this->t('Loop: %loop', ['%loop' => $this->getSetting('loop') ? $this->t('yes') : $this->t('no')]);
    switch ($this->getSetting('data-speed-icons')) {
      case 'animals':
        $summary[] = $this->t('Speed icons: animals');
        break;

      case 'arrows':
        $summary[] = $this->t('Speed icons: arrows');
        break;
    }
    return $summary;
  }

  /**
   * Prepare the attributes according to the settings.
   *
   * @param string[] $additional_attributes
   *   Additional attributes to be applied to the HTML element. Attribute names
   *   will be used as key and value in the HTML element.
   *
   * @return \Drupal\Core\Template\Attribute
   *   Container with all the attributes for the HTML tag.
   */
  protected function prepareAttributes(array $additional_attributes = []) {
    $attributes = new Attribute();
    foreach (['autoplay', 'loop'] + $additional_attributes as $attribute) {
      if ($this->getSetting($attribute)) {
        $attributes->setAttribute($attribute, $attribute);
      }
    }
    $attributes->setAttribute('data-speed-icons', $this->getSetting('data-speed-icons'));
    return $attributes;
  }

}
