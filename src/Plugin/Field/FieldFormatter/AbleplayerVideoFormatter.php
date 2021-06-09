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
    return parent::prepareAttributes(['transcript']);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
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
        $poster = render($parent->ableplayer_poster_image->view(['type' => 'ableplayer_poster_image']));
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
    $data_sign_src = render($parent->ableplayer_sign_language->view(['type' => 'ableplayer_sign_language']));

    foreach ($source_files as $source_file) {
      foreach ($source_file as $element) {
        $element['source_attributes']->setAttribute('data-sign-src', $data_sign_src);
      }
    }

    return $source_files;
  }

}
