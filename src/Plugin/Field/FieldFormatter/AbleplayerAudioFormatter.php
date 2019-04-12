<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

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

}
