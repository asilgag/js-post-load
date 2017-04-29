<?php

namespace Drupal\js_post_load\Service;

/**
 * Functions for this module
 */
class JsPostLoadService {

  /**
   * Check if module is enabled
   *
   * @return boolean
   */
  public function isEnabled() {
    $config = \Drupal::config('js_post_load.settings');
    return (bool) $config->get('enabled');
  }

  /**
   * Check if entity id is excluded
   *
   * @param int $entityId
   *
   * @return boolean
   */
  public function isEntityIdExcluded($entityId) {
    $config = \Drupal::config('js_post_load.settings');
    $excludedIds = explode("\n", $config->get('excluded_ids'));
    $excludedIds = array_map(function($item) {
      return trim($item);
    }, $excludedIds);
    return (
      is_array($excludedIds) &&
      in_array($entityId, $excludedIds)
    );
  }

}
