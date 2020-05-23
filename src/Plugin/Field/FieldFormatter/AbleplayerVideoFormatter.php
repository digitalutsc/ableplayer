<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;

use Drupal\file\Plugin\Field\FieldFormatter\FileMediaFormatterBase;

/**
 * Plugin implementation of the 'ableplayer_video' formatter.
 *
 * @FieldFormatter(
 *   id = "ableplayer_video",
 *   label = @Translation("Ableplayer"),
 *   description = @Translation("Display the file using Ableplayer."),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class AbleplayerVideoFormatter extends FileMediaFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function getMediaType() {
    return 'video';
  }

  /**
   * {@inheritdoc}
   */
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
