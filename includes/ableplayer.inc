<?php

/**
 * @file Helper functions for the Able Player module.
 *
 * These functions are intended to be used internally by Able Player and its
 * submodules. This file should neither be loaded in global scope nor relied
 * upon by third-party code.
 */

/**
 * Function returns the complete list of mimetypes supported by Able Player.
 *
 * @return array Able Player supported mimetypes.
 */
function ableplayer_mimetypes() {
  return array(
    ABLEPLAYER_BUNDLE_AUDIO . '/mpeg',
    ABLEPLAYER_BUNDLE_AUDIO . '/ogg',
    ABLEPLAYER_BUNDLE_VIDEO . '/mp4',
    ABLEPLAYER_BUNDLE_VIDEO . '/webm',
  );
}

/**
 * Able Player default player settings.
 *
 * @return array Able Player file formatter default settings.
 */
function ableplayer_default_settings() {
  return array(
    'width' => 480,
    'height' => 360,
    'autoplay' => FALSE,
    'preload' => FALSE,
    'show_now_playing' => FALSE,
    'use_transcript_button' => FALSE,
    'lyrics_mode' => FALSE,
    'volume' => 0.5,
    'start_time' => 0,
    'seek_interval' => FALSE,
    'transcript_title' => FALSE,
  );
}

/**
 * Construct settings array for Able Player file formatter.
 *
 * Build the settings array from the configured values, or use the defaults.
 * The result will be used to configure the display of the Able Player file
 * formatter.
 *
 * @return array Able Player file formatter view settings.
 */
function ableplayer_set_view_settings($file, $display) {
  $options = array();

  foreach (array_keys(ableplayer_default_settings()) as $setting) {
    $options[$setting] = isset($file->override[$setting]) ?
      $file->override[$setting] :
      $display['settings'][$setting];
  }

  return $options;
}

/**
 * Helper function to return information about the Able Player installation.
 *
 * Files that are not found installed in the expected location are colletected
 * and returned.
 *
 * @return array $status Hash of status information.
 *   - 'error' indicates whether an error was detected via a boolean value.
 *   - 'version' is set to the detected version number of Able Player.
 *   - 'message' set if an error was detected.
 */
function ableplayer_installation_status() {
  $status = array();
  $version = NULL;
  $message = NULL;

  $ableplayer = libraries_detect('ableplayer');

  $files = array_keys(array_merge(
    $ableplayer['files']['js'],
    $ableplayer['files']['css']
  ));

  $missing_files = array();

  foreach ($files as $file) {
    $path = $ableplayer['library path'] . '/' . $file;

    if (!is_file($path) || !is_readable($path)) {
      $missing_files[$path] = t('The file') . ' <em>' . t('@file',
        array('@file' => $path)) . '</em> ' . t('was not found.');
    }
  }

  if (!empty($missing_files)) {
    $message = trim(implode(' ', $missing_files));
  }
  elseif (!$ableplayer['installed']) {
    $message = t($ableplayer['error message']);
  }
  else {
    $version = $ableplayer['version'];
  }

  $status['error'] = !empty($message);
  $status['version'] = $version;
  $status['message'] = $message;

  return $status;
}

/**
 * Get data for populating track elements for Able Player.
 *
 * Given a list of transcript file entities, look up their language in the core
 * language list to get the native language label. This allows the srclang and
 * label attributes to be set on the track element of the field formatter
 * output, which Able Player uses to display a select list of available
 * transcript languages.
 *
 * @return array $transcripts Hash of transcript information
 *   - 'code' the ISO language code as used internally by Drupal
 *   - 'label' the native language name as used internally by Drupal
 *   - 'uri' the path to the transcript file
 */
function ableplayer_build_track_list($transcript_items) {
  $entity_translation = module_exists('entity_translation');
  $transcripts = array();

  if ($entity_translation) {
    $languages = language_list();
  }

  foreach ($transcript_items as $item) {
    $code = '';
    $label = '';
    $uri = file_create_url($item['uri']);

    if ($entity_translation && array_key_exists('translations', $item)) {
      $code = $item['translations']->original;
      $label = $languages[$code]->native;
    }

    $transcripts[] = array(
      'code' => $code,
      'label' => $label,
      'uri' => $uri,
    );
  }

  return $transcripts;
}
