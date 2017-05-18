<?php

namespace Drupal\js_post_load\Render;

use Drupal\Core\Asset\AttachedAssetsInterface;
use Drupal\Core\Render\Markup;

/**
 * {@inheritdoc}
 */
class HtmlResponseAttachmentsProcessor extends \Drupal\Core\Render\HtmlResponseAttachmentsProcessor {



  /**
   * {@inheritdoc}
   */
  protected function processAssetLibraries(AttachedAssetsInterface $assets, array $placeholders) {
    // Get parent's assets
    $variables = parent::processAssetLibraries($assets, $placeholders);

    // Disabled for non-anonymous visits
    if (!\Drupal::currentUser()->isAnonymous()) {
      return $variables;
    }

    // Get module's service
    $jsPostLoadService = \Drupal::service('js_post_load');

    // Check if module is enabled
    if (!$jsPostLoadService->isEnabled()) {
      return $variables;
    }

    $entity = null;
    $entityId = null;

    // Get current entity's data
    // Try node and taxonomy_term
    $entitiesToTry = ['node', 'taxonomy_term'];
    foreach ($entitiesToTry as $entityToTry) {
      $entity = \Drupal::routeMatch()->getParameter($entityToTry);
      if ($entity) {
        break;
      }
    }

    if ($entity){
      $entityId = $entity->id();
    } else {
      return $variables;
    }

    // Check if this entity id is excluded
    if ($jsPostLoadService->isEntityIdExcluded($entityId)) {
      return $variables;
    }

    // get all JS paths to be loaded at page bottom
    $scriptsToLoad = [];
    foreach($variables['scripts_bottom'] as $key => $scriptsBottom) {
      if (!empty($scriptsBottom['#attributes']['src'])) {
        $scriptsToLoad[] = $scriptsBottom['#attributes'];
        unset($variables['scripts_bottom'][$key]);
      }
    }
    // create a JS array with objects to call downloadJSAtOnload() function
    $scriptsToLoadObject = [];
    foreach ($scriptsToLoad as $scriptToLoad) {
      $scriptToLoadObject  = '{';
      $scriptToLoadObject .= 'src:"' . $scriptToLoad['src'] . '",';
      $scriptToLoadObject .= 'async:' . (isset($scriptToLoad['async']) ? $scriptToLoad['async'] : 'false') . ',';
      $scriptToLoadObject .= 'defer:' . (isset($scriptToLoad['defer']) ? $scriptToLoad['defer'] : 'false');
      $scriptToLoadObject .= '}';
      $scriptsToLoadObject[] = $scriptToLoadObject;
    }
    $scriptsToLoadArray = '['.implode(',', $scriptsToLoadObject).']';

    // Add varvy.com's downloadJSAtOnload
    // (https://varvy.com/pagespeed/defer-loading-javascript.html)
    $themePath = drupal_get_path('module', 'js_post_load');
    $downloadJSAtOnloadContent = file_get_contents(
      $themePath.'/includes/downloadJSAtOnload.min.js'
    );
    $variables['scripts_bottom'][] = [
      '#markup' => Markup::create(
        '<script>'.
        str_replace('scripts', $scriptsToLoadArray, $downloadJSAtOnloadContent).
        '</script>'
      )
    ];

    return $variables;
  }

}
