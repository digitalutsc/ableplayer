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
  $variables['data_translation_path'] = url(libraries_get_path('ableplayer') . '/translations/', array('absolute' => TRUE, ));
  $variables['id'] = drupal_html_id($variables['id']);
}

/**
 * Theme function for Able Player.
 */
function theme_ableplayer($variables) {
  extract($variables);

  if (strpos($mimetype, 'video') > -1) {
    $open_tag = '<video ';
    $close_tag = '</video>';
  }
  elseif (strpos($mimetype, 'audio') > -1) {
    $open_tag = '<audio ';
    $close_tag = '</audio>';
  }
  else {
    return '';
  }

  $output = $open_tag;
  $output .= 'id="ableplayer-' . $id . '" ';
  $output .= 'width="' . $options['width'] . '" ';
  $output .= 'height="' . $options['height'] . '" ';

  if ($options['autoplay']) {
    $output .= 'autoplay ';
  }

  if ($options['preload']) {
    $output .= 'preload ';
  }

  if ($options['show_now_playing']) {
    $output .= 'data-show-now-playing ';
  }

  if ($options['use_transcript_button']) {
    $output .= 'data-use-transcript-button ';
  }

  if ($options['lyrics_mode']) {
    $output .= 'data-lyrics-mode ';
  }

  $output .= 'data-volume="' . $options['volume'] . '" ';

  if ($options['start_time']) {
    $output .= 'data-start-time="' . $options['start_time'] . '" ';
  }

  if ($options['seek_interval']) {
    $output .= 'data-seek-interval="' . $options['seek_interval'] . '" ';
  }

  if ($options['transcript_title']) {
    $output .= 'data-transcript-title="' . $options['transcript_title'] . '" ';
  }

  $output .= 'data-translation-path="' . $data_translation_path . '" ';

  if (isset($data_youtube_id)) {
    $output .= 'data-youtube-id="' . $data_youtube_id . '" ';
  }

  $output .= 'data-debug ';
  $output .= 'data-able-player>';

  if (empty($data_youtube_id)) {
    $output .= '<source type="' . $mimetype . '" src="' . $uri . '" />';
  }

  $output .= $close_tag;

  return $output;
}
