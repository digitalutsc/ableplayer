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

  $variables['id'] = \Drupal\Component\Utility\Html::getId($variables['id']);
}
