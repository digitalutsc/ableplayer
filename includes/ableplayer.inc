<?php

/**
 * @file
 * ableplayer.inc
 *
 * Helper functions for the Able Player module.
 */

/**
 * Able Player supported video mimetypes.
 */
function ableplayer_mimetypes() {
  return array(
    'audio/mpeg',
    'audio/ogg',
    'video/mp4',
    'video/webm',
  );
}

/**
 * Able Player default settings.
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
 * Build settings array for the current file to be displayed.
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
