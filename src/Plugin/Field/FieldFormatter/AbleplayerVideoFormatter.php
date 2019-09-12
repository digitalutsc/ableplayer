<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

use Drupal\Component\Plugin\PluginManagerInterface;

use Drupal\file\Plugin\Field\FieldFormatter\FileMediaFormatterBase;

use Symfony\Component\DependencyInjection\ContainerInterface;

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
class AbleplayerVideoFormatter extends FileMediaFormatterBase implements ContainerFactoryPluginInterface {

  /**
   * The formatter plugin manager.
   *
   * @var \Drupal\Core\Field\FormatterPluginManager
   */
  protected $pluginManager;

  /**
   * {@inheritdoc}
   */
  public static function getMediaType() {
    return 'video';
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('plugin.manager.field.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, $plugin_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->pluginManager = $plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    $parent = $items->getEntity();
    $transcript_items = $parent->ableplayer_transcript;

    $options = [
      'field_definition' => $transcript_items->getFieldDefinition(),
      'view_mode' => $this->viewMode,
      'configuration' => [
        'type' => 'ableplayer_transcript',
      ],
    ];

    $formatter = $this->pluginManager->getInstance($options);
    $transcripts = $formatter->viewElements($transcript_items, $langcode);

    foreach ($elements as &$element) {
      $element['#transcripts'] = $transcripts;
    }

    return $elements;
  }

}
