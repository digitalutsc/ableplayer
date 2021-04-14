<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\file\Plugin\Field\FieldFormatter\FileMediaFormatterBase;
use Drupal\media\OEmbed\UrlResolver;
use Drupal\media\OEmbed\UrlResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'ableplayer_remote_video' formatter.
 *
 * @FieldFormatter(
 *   id = "ableplayer_remote_video",
 *   label = @Translation("Ableplayer"),
 *   description = @Translation("Display the remote file using Ableplayer."),
 *   field_types = {
 *     "link",
 *     "string",
 *     "string_long",
 *   }
 * )
 */
class AbleplayerRemoteVideoFormatter extends FormatterBase
{

  /**
   * The oEmbed URL resolver service.
   *
   * @var \Drupal\media\OEmbed\UrlResolverInterface
   */
  protected $urlResolver;

  /**
   * Constructs a FormatterBase object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\media\OEmbed\UrlResolverInterface $url_resolver
   *   The oEmbed URL resolver service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, UrlResolverInterface $url_resolver)
  {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->urlResolver = $url_resolver;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('media.oembed.url_resolver')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function getMediaType()
  {
    return 'remote_video';
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition)
  {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $element = [];

    foreach ($items as $delta => $item) {
      $main_property = $item->getFieldDefinition()->getFieldStorageDefinition()->getMainPropertyName();
      $value = $item->{$main_property};

      if (empty($value)) {
        continue;
      }

      $provider = $this->urlResolver->getProviderByUrl($value);

      if ($provider->getName() === 'YouTube') {
        $scheme = 'https://*.youtube.com/watch*';
        $regexp = str_replace(['.', '*'], ['\.', '.*'], $scheme);
        if (preg_match("|^$regexp$|", $value)) {
          $parts = UrlHelper::parse($value);
          $id = $parts['query']['v'];
        }
        /*
        * Currently YouTube returns a 404 for this pattern so this code is
        * never called.
        */

        $scheme = 'https://*.youtube.com/v/*';
        $regexp = str_replace(['.', '*'], ['\.', '.*'], $scheme);
        if (preg_match("|^$regexp$|", $value)) {
          $parts = UrlHelper::parse($value);
          $path = explode('/', $parts);
          $id = $path[2];
        }

        $scheme = 'https://youtu.be/*';
        $regexp = str_replace(['.', '*'], ['\.', '.*'], $scheme);
        if (preg_match("|^$regexp$|", $value)) {
          $parts = parse_url($value, PHP_URL_PATH);
          $path = explode('/', $parts);
          $id = $path[1];
        }
      }

      if ($provider->getName() === 'Vimeo') {
        $scheme = 'https://vimeo.com/*';
        $regexp = str_replace(['.', '*'], ['\.', '.*'], $scheme);
        if (preg_match("|^$regexp$|", $value)) {
          $parts = parse_url($value, PHP_URL_PATH);
          $path = explode('/', $parts);
          $id = $path[1];
        }
      }
      /*while (ob_get_level() != 0) {
        ob_end_clean();
      }
      echo '<pre>';
      echo $value;
      echo "\n";
      echo var_export($id, TRUE);
      echo '</pre>';
      die();
*/
      if ($provider->getName() === 'YouTube') {
        $element[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'video',
          '#attributes' => [
            'data-able-player' => '',
            'data-youtube-id' => $id,
          ],
        ];
      }
      if ($provider->getName() === 'Vimeo') {
        $element[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'video',
          '#attributes' => [
            'data-able-player' => '',
            'data-vimeo-id' => $id,
          ],
        ];
      }
    }
    return $element;
  }
}
