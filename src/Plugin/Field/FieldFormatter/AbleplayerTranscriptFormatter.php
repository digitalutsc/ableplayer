<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

use Drupal\core\Field\FieldItemListInterface;
use Drupal\file\Plugin\Field\FieldFormatter\FileFormatterBase;

/**
 * Plugin implementation of the 'ableplayer_video' formatter.
 *
 * @FieldFormatter(
 *   id = "ableplayer_transcript",
 *   label = @Translation("Ableplayer Transcript"),
 *   description = @Translation("Display media transcript"),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class AbleplayerTranscriptFormatter extends FileFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function view(FieldItemListInterface $items, $langcode = NULL) {
    $elements = parent::view($items, $langcode);

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $entities = $this->getEntitiesToView($items, $langcode);

    foreach ($entities as $delta => $file) {
      $elements[$delta] = [
        '#theme' => $this->getPluginId(),
        '#file' => $file,
      ];
    }

    return $elements;
  }

}
