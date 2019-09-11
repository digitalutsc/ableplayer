<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

use Drupal\core\Field\FieldItemListInterface;
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
  public function view(FieldItemListInterface $items, $langcode = NULL) {
    $elements = parent::view($items, $langcode);

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $plugin_manager = \Drupal::service('plugin.manager.field.formatter');

    $parent = $items->getEntity();
    $transcript_items = $parent->ableplayer_transcript;

    $options = array(
      'field_definition' => $transcript_items->getFieldDefinition(),
      'view_mode' => $this->viewMode,
      'configuration' => array(
        'type' => 'ableplayer_transcript',
      ),
    );

    $formatter = $plugin_manager->getInstance($options);
    $transcripts = $formatter->view($transcript_items, $langcode);

    foreach ($elements as &$element) {
      $element['#transcripts'] = array($transcripts);
    }

    return $elements;
  }

}
