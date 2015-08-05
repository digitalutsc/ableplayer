<?php

/**
 * @file
 *
 * Theme functions for Able Player.
 */

/**
 * Implements hook_preprocess_THEME().
 */
function ableplayer_preprocess_ableplayer(&$variables) {
  $variables['data_translation_path'] = url(libraries_get_path('ableplayer') . '/translations/', array('absolute' => TRUE));
  $variables['id'] = drupal_html_id($variables['id']);

  switch ($variables['options']['preload']) {

    case 0:
      $variables['options']['preload'] = 'metadata';

      break;

    case 1:
      $variables['options']['preload'] = 'auto';

      break;

  }
}

/**
 * Theme function for Able Player.
 */
function theme_ableplayer($variables) {
  if (strpos($variables['mimetype'], ABLEPLAYER_BUNDLE_VIDEO) > -1) {
    $open_tag = '<video ';
    $close_tag = '</video>';
  }
  elseif (strpos($variables['mimetype'], ABLEPLAYER_BUNDLE_AUDIO) > -1) {
    $open_tag = '<audio ';
    $close_tag = '</audio>';
  }
  else {
    return '';
  }

  $output = $open_tag;
  $output .= 'id="ableplayer-' . $variables['id'] . '" ';
  $output .= 'width="' . $variables['options']['width'] . '" ';
  $output .= 'height="' . $variables['options']['height'] . '" ';

  if ($variables['options']['autoplay']) {
    $output .= 'autoplay ';
  }

  if ($variables['options']['show_now_playing']) {
    $output .= 'data-show-now-playing ';
  }

  if ($variables['options']['use_transcript_button']) {
    $output .= 'data-use-transcript-button="false" ';
  }

  if ($variables['options']['lyrics_mode']) {
    $output .= 'data-lyrics-mode ';
  }

  $output .= 'data-volume="' . $variables['options']['volume'] . '" ';

  if ($variables['options']['preload']) {
    $output .= 'preload="' . $variables['options']['preload'] . '" ';
  }

  if ($variables['options']['start_time']) {
    $output .= 'data-start-time="' . $variables['options']['start_time'] . '" ';
  }

  if ($variables['options']['seek_interval']) {
    $output .= 'data-seek-interval="' . $variables['options']['seek_interval'] . '" ';
  }

  if ($variables['options']['transcript_title']) {
    $output .= 'data-transcript-title="' . $variables['options']['transcript_title'] . '" ';
  }

  $output .= 'data-translation-path="' . $variables['data_translation_path'] . '" ';

  if (isset($variables['data_youtube_id'])) {
    $output .= 'data-youtube-id="' . $variables['data_youtube_id'] . '" ';
  }

  $output .= 'data-debug ';
  $output .= 'data-able-player>';

  if (empty($variables['data_youtube_id'])) {
    $output .= '<source type="' . $variables['mimetype'] . '" src="' . $variables['uri'] . '" />';

    foreach ($variables['transcripts'] as $transcript) {
      $output .= '<track kind="captions" src="' . $transcript['uri'];
      $output .= '" srclang="' . $transcript['code'];
      $output .= '" label="' . $transcript['label'] . '" />';
    }

    foreach ($variables['transcripts'] as $description) {
      $output .= '<track kind="descriptions" src="' . $description['uri'];
      $output .= '" srclang="' . $description['code'];
      $output .= '" label="' . $description['label'] . '" />';
    }
  }

  $output .= $close_tag;

  return $output;
}
