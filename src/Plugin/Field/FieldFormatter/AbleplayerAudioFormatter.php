<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Field\FieldItemListInterface;

use Drupal\file\Plugin\Field\FieldFormatter\FileMediaFormatterBase;

/**
 * Plugin implementation of the 'ableplayer_audio' formatter.
 *
 * @FieldFormatter(
 *   id = "ableplayer_audio",
 *   label = @Translation("Ableplayer"),
 *   description = @Translation("Display the file using Ableplayer."),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class AbleplayerAudioFormatter extends FileMediaFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function getMediaType() {
    return 'audio';
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'controls' => FALSE,
      'autoplay' => FALSE,
      'loop' => FALSE,
    ] + parent::defaultSettings();
  }

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $parent = $items->getEntity();

    if ($parent->hasField('ableplayer_caption')) {
      foreach ($elements as &$element) {
        $element['#caption'] = $parent->ableplayer_caption->view(['type' => 'ableplayer_caption']);
      }
    }

    return $elements;
  }

}
