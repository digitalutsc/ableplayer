<?php

/**
 * @file
 *
 * Formatter settings for Able Player file display.
 */

/**
 * Implements hook_file_formatter_info().
 */
function ableplayer_file_formatter_info() {
  $formatters = array();

  module_load_include('inc', 'ableplayer', 'includes/ableplayer');

  $formatters['ableplayer'] = array(
    'label' => t('Able Player'),
    'description' => t('Display an audio or video file in Able Player (file formatter)'),
    'file types' => array(ABLEPLAYER_BUNDLE_AUDIO, ABLEPLAYER_BUNDLE_VIDEO, ),
    'default settings' => ableplayer_default_settings(),
    'view callback' => 'ableplayer_file_formatter_ableplayer_view',
    'settings callback' => 'ableplayer_file_formatter_ableplayer_settings',
    'mime types' => ableplayer_mimetypes(),
  );

  return $formatters;
}

/**
 * Impelemtnts hook_file_formatter_FORMATTER_view().
 *
 * This is a recommended callback naming scheme, not a true hook function.
 */
function ableplayer_file_formatter_ableplayer_view($file, $display, $langcode) {
  $theme = 'ableplayer';
  $items = field_get_items('file', $file, 'field_transcript');
  $item = array_shift($items);
  $transcript = file_create_url($item['uri']);
  $options = ableplayer_set_view_settings($file, $display);

  module_load_include('inc', 'ableplayer', 'includes/ableplayer');

  switch ($file->filemime) {

    case ABLEPLAYER_BUNDLE_AUDIO . '/mpeg':
    case ABLEPLAYER_BUNDLE_AUDIO . '/ogg':
      $element = array(
        '#theme' => $theme,
        '#id' => $file->fid,
        '#uri' => file_create_url($file->uri),
        '#mimetype' => $file->filemime,
        '#transcript' => $transcript,
        '#options' => $options,
      );

      return $element;

    case ABLEPLAYER_BUNDLE_VIDEO . '/youtube':
      $element = array(
        '#theme' => $theme,
        '#id' => $file->fid,
        '#uri' => $file->uri,
        '#mimetype' => $file->filemime,
        '#transcript' => NULL,
        '#options' => $options,
      );

      return $element;

    case ABLEPLAYER_BUNDLE_VIDEO . '/mp4':
    case ABLEPLAYER_BUNDLE_VIDEO . '/webm':
      $element = array(
        '#theme' => $theme,
        '#id' => $file->fid,
        '#uri' => file_create_url($file->uri),
        '#mimetype' => $file->filemime,
        '#transcript' => $transcript,
        '#options' => $options,
      );

      return $element;

  }
}

/**
 * Implements hook_file_formatter_FORMATTER_settings().
 *
 * This is a recommended callback naming scheme, not a true hook function.
 */
function ableplayer_file_formatter_ableplayer_settings($form, &$form_state, $settings) {
  $element = array();

  $element['width'] = array(
    '#title' => t('Width'),
    '#type' => 'textfield',
    '#type' => 'textfield',
    '#default_value' => $settings['width'],
    '#description' => t(
      'Width of the media player in pixels. For video, this value should reflect
      the target width of the media itself.'),
    '#size' => 6,
    '#required' => TRUE,
    '#element_validate' => array('ableplayer_validate_dimensions'),
  );

  $element['height'] = array(
    '#title' => t('Height'),
    '#type' => 'textfield',
    '#default_value' => $settings['height'],
    '#description' => t(
      'Height of the video in pixels. If not provided will default to 360.'),
    '#size' => 6,
    '#required' => TRUE,
    '#element_validate' => array('ableplayer_validate_dimensions'),
  );

  $element['autoplay'] = array(
    '#title' => t('Autoplay video on load'),
    '#type' => 'checkbox',
    '#default_value' => $settings['autoplay'],
    '#description' => t(
      'Play media automatically when page loads. For accessibility reasons, this
      is not recommended unless user is sure to expect media to automatically
      start. For example, autoplay could reasonably be used in conjunction with
      data-start-time in a media search application.'),
  );

  $element['preload'] = array(
    '#title' => t('Preload video'),
    '#type' => 'checkbox',
    '#default_value' => $settings['preload'],
    '#description' => t(
      'Tells the browser how much media to download when the page loads. If the
      media is the central focus of the web page, use preload=“auto”, which
      instructs the browser to download as much of the media as possible. If the
      media is not a central focus, downloading the entire media resource can
      consume valuable bandwidth, so preload=“metadata” would be a better
      option.'),
  );

  $element['show_now_playing'] = array(
    '#title' => t('Show now playing'),
    '#type' => 'checkbox',
    '#default_value' => $settings['show_now_playing'],
    '#description' => t(
      'include "Selected track" section within player; only applies when a
      playlist is present.'),
  );

  $element['use_transcript_button'] = array(
    '#title' => t('Use transcript button'),
    '#type' => 'checkbox',
    '#default_value' => $settings['use_transcript_button'],
    '#description' => t(
      'Uncheck to exclude transcript button from controller.  If using the
      data-transcript-div attribute to write the transcript to an external
      container (e.g., on a dedicated transcript page), you might not want users
      to be able to toggle the transcript off.'),
  );

  $element['lyrics_mode'] = array(
    '#title' => t('Lyrics mode'),
    '#type' => 'checkbox',
    '#default_value' => $settings['lyrics_mode'],
    '#description' => t(
      'Forces a line break between and within captions in the transcript.'),
  );

  $element['volume'] = array(
    '#title' => t('Volume'),
    '#type' => 'textfield',
    '#default_value' => $settings['volume'],
    '#size' => 6,
    '#description' => t(
      'Set the default volume (0 to 1; default is 0.5 to avoid overpowering
      screen reader audio).'),
    '#element_validate' => array('ableplayer_validate_volume'),
  );

  $element['start_time'] = array(
    '#title' => t('Start time'),
    '#type' => 'textfield',
    '#size' => 6,
    '#description' => t(
      'Time at which you want the audio to start playing (in seconds).'),
  );

  $element['seek_interval'] = array(
    '#title' => t('Seek interval'),
    '#type' => 'textfield',
    '#size' => 6,
    '#description' => t(
      'Interval (in seconds) of forward and rewind buttons. By default, seek
      interval is calculated to be 1/10 of the duration of media.'),
  );

  $element['transcript_title'] = array(
    '#title' => t('Transcript title'),
    '#type' => 'textfield',
    '#size' => 18,
    '#description' => t('Override default transcript title (default is
    "Transcript", or "Lyrics" if the data-lyrics-mode attribute is present).'),
  );

  return $element;
}

/**
 * Validate that video dimensions are numbers or "auto".
 */
function ableplayer_validate_dimensions($element, &$form_state, $form) {
  if (!empty($element['#value']) && !preg_match('/^(auto|[0-9]*)$/', $element['#value'])) {
    form_error($element, t(
      'The value entered for @dimension is invalid. Please insert a unitless integer for pixels or "auto".',
      array('@dimension' => $element['#title'], )));
  }
}

/**
 * Validate that the volume is a number between 0 and 1 inclusive.
 */
function ableplayer_validate_volume($element, &$form_state, $form) {
  if (!empty($element['#value'])) {
    if (!is_numeric($element['#value']) || $element['#value'] < 0 || $element['#value'] > 1) {
      form_error($element, t(
        'The value entered for @volume is invalid. Please insert a number between 0 and 1.',
        array('@volume' => $element['#title'], )));
    }
  }
}

/**
 * Validate that the seek interval is a number.
 */
function ableplayer_validate_seek_interval($element, &$form_state, $form) {
  if (!empty($element['#value'])) {
    if (!is_numeric($element['#value'])) {
      form_error($element, t(
        'The value entered for @seek is invalid. Please insert a number (seconds).',
        array('@seek' => $element['#title'], )));
    }
  }
}

/**
 * Validate that the start time setting is an integer (seconds) and greater than
 * 0.
 */
function ableplayer_validate_start_time($element, &$form_state, $form) {
  if (!empty($element['#value'])) {
    if (!is_integer($element['#value']) || $element['#value'] < 0) {
      form_error($element, t(
        'The value entered for @start is invalid. Please insert a positive integer (seconds).',
        array('@start' => $element['#title'], )));
    }
  }
}
