<?php
namespace Drupal\js_post_load;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Modifies the \Drupal\Core\Asset\CssCollectionRenderer service.
 */
class JsPostLoadServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Overrides html_response.attachments_processor to load JS links after onLoad event
    $definition = $container->getDefinition('html_response.attachments_processor');
    $definition->setClass('Drupal\js_post_load\Render\HtmlResponseAttachmentsProcessor');
  }
}