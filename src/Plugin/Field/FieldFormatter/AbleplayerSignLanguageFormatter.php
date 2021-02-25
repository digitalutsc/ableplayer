<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

use Drupal\file\Plugin\Field\FieldFormatter\FileFormatterBase;

/**
 * Plugin implementation of the 'ableplayer_video' formatter.
 *
 * @FieldFormatter(
 *   id = "ableplayer_sign_language",
 *   label = @Translation("Sign Language"),
 *   description = @Translation("Display media sign language video"),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class AbleplayerSignLanguageFormatter extends FileFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return $field_definition->getName() === 'ableplayer_sign_language';
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
