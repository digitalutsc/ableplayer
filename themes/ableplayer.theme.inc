<?php

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Render\Element;

/**
 * @file
 * Theme functions for Able Player.
 */

/**
 * Implements hook_preprocess_THEME().
 */
function ableplayer_preprocess_ableplayer(&$variables) {
  $variables['options']['caption_title'] = Html::escape(Xss::filter($variables['options']['caption_title']));

  $variables['id'] = Html::getId($variables['id']);
}
