<?php

use Drupal\Core\Render\Element;

/**
 * @file
 * Theme functions for Able Player.
 */

/**
 * Implements hook_preprocess_THEME().
 */
function ableplayer_preprocess_ableplayer(&$variables) {
  $variables['options']['transcript_title'] = \Drupal\Component\Utility\Html::escape(\Drupal\Component\Utility\Xss::filter($variables['options']['transcript_title']));
// @FIXME
// url() expects a route name or an external URI.
// $variables['translation_path'] = url(libraries_get_path('ableplayer') . '/translations/', array('absolute' => TRUE));

  $variables['id'] = \Drupal\Component\Utility\Html::getId($variables['id']);
}
