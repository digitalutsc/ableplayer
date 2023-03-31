<?php

namespace Drupal\ableplayer\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

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
  public static function defaultSettings() {
    return [
      'viewer' => FALSE,
      'transcript' => FALSE,
      'controls' => FALSE,
      'autoplay' => FALSE,
      'loop' => FALSE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      'viewer' => [
        '#title' => $this
          ->t('Display Transcript Container by Default'),
        '#type' => 'checkbox',
        '#default_value' => $this
          ->getSetting('viewer'),
      ],
      'transcript' => [
        '#title' => $this->t('Draggable Transcript Container'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('transcript'),
        ]
      ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareAttributes(array $additional_attributes = []) {
    return parent::prepareAttributes(['viewer', 'transcript']);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this
    ->t('Display Transcript Container by Default: %viewer', [
      '%viewer' => $this->getSetting('viewer') ? $this->t('yes') : $this->t('no'),
  ]);
    $summary[] = $this
      ->t('Draggable Transcript Container: %transcript', [
        '%transcript' => $this->getSetting('transcript') ? $this->t('yes') : $this->t('no'),
      ]);
      return $summary;
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

    if ($parent->hasField('ableplayer_chapter')) {
      foreach ($elements as &$element) {
        $element['#chapter'] = $parent->ableplayer_chapter->view(['type' => 'ableplayer_chapter']);
      }
    }

    if ($parent->hasField('ableplayer_poster_image')) {
      foreach ($elements as &$element) {
        $poster_array = $parent->ableplayer_poster_image->view(['type' => 'ableplayer_poster_image']);
        $poster = render($poster_array);
        $element['#attributes']->setAttribute('poster', $poster);
      }
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  protected function getSourceFiles(EntityReferenceFieldItemListInterface $items, $langcode) {
    $source_files = parent::getSourceFiles($items, $langcode);
    $parent = $items->getEntity();
    $data_sign_src_array = $parent->ableplayer_sign_language->view(['type' => 'ableplayer_sign_language']);
    $data_sign_src = render($data_sign_src_array);

    foreach ($source_files as $source_file) {
      foreach ($source_file as $element) {
        $element['source_attributes']->setAttribute('data-sign-src', $data_sign_src);
      }
    }

    return $source_files;
  }

}
